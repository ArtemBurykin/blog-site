<?php

namespace App\Tests\Controller\Editorjs;

use App\Controller\Editorjs\UploadFileController;
use App\Entity\User;
use App\Tests\Traits\DependenciesTrait;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class UploadFileControllerTest extends WebTestCase
{
    use DependenciesTrait;

    public function testFailNotAuthenticated()
    {
        $client = static::createClient();

        [$file, $uploadedFile] = $this->createTempFile('file.txt');

        $client->request(Request::METHOD_POST, $this->getUrl(), [], ['file' => $uploadedFile]);
        $response = $client->getResponse();

        $this->assertEquals(Response::HTTP_UNAUTHORIZED, $response->getStatusCode());

        $data = json_decode($response->getContent(), true);
        $this->assertEquals(
            ['success' => 0],
            $data
        );

        fclose($file);
    }

    public function testFailUserNotAdmin()
    {
        $client = static::createClient();

        $user = new User();
        $email = 'test@example.com';
        $user->setEmail($email);
        $user->setPassword('1234455');
        $this->getUserRepository()->save($user);

        $this->getEntityManager()->clear();

        [$file, $uploadedFile] = $this->createTempFile('file.txt');

        $client->loginUser($user);
        $client->request(Request::METHOD_POST, $this->getUrl(), [], ['file' => $uploadedFile]);
        $response = $client->getResponse();

        $this->assertEquals(Response::HTTP_FORBIDDEN, $response->getStatusCode());

        $data = json_decode($response->getContent(), true);
        $this->assertEquals(
            ['success' => 0],
            $data
        );

        fclose($file);
    }

    public function testFailEmptyRequest()
    {
        $client = static::createClient();

        $this->getEntityManager()->clear();

        $user = new User();
        $email = 'test@example.com';
        $user->setEmail($email);
        $user->setPassword('123456');
        $user->setRoles(['ROLE_ADMIN']);
        $this->getUserRepository()->save($user);

        $client->loginUser($user);
        $client->request(Request::METHOD_POST, $this->getUrl());

        $response = $client->getResponse();

        $this->assertEquals(Response::HTTP_BAD_REQUEST, $response->getStatusCode());

        $data = json_decode($response->getContent(), true);
        $this->assertEquals(
            ['success' => 0],
            $data
        );
    }

    public function testSuccessful()
    {
        $client = static::createClient();

        $user = new User();
        $email = 'test@example.com';
        $user->setEmail($email);
        $user->setPassword('123456');
        $user->setRoles(['ROLE_ADMIN']);
        $this->getUserRepository()->save($user);

        $originalName = 'some-file.txt';
        [$file, $uploadedFile] = $this->createTempFile($originalName);

        $this->getEntityManager()->clear();

        $client->loginUser($user);
        $client->request(Request::METHOD_POST, $this->getUrl(), [], ['file' => $uploadedFile]);
        $response = $client->getResponse();

        $this->assertEquals(Response::HTTP_CREATED, $response->getStatusCode());

        $data = json_decode($response->getContent(), true);
        $this->assertEquals(1, $data['success']);
        $this->assertStringContainsString('/uploads/content/some-file', $data['file']['url']);

        $fileUrl = $data['file']['url'];
        $projDir = static::getContainer()->getParameter('kernel.project_dir');
        $filePath = $projDir.'/public/'.$fileUrl;
        $this->assertFileExists($filePath);

        fclose($file);
        unlink($filePath);
    }

    public function testFailFileTooLarge()
    {
        $client = static::createClient();

        $user = new User();
        $email = 'test@example.com';
        $user->setEmail($email);
        $user->setRoles(['ROLE_ADMIN']);
        $user->setPassword('123455');
        $this->getUserRepository()->save($user);

        $this->getEntityManager()->clear();

        $client->loginUser($user);

        // 2MB + 1byte
        $uploadedFile = $this->createStub(UploadedFile::class);
        $uploadedFile
            ->method('getSize')
            ->willReturn(2097153);
        // создаем реквест в ручную для того, чтобы иметь возможность использовать мок.
        $request = Request::create($this->getUrl(), Request::METHOD_POST, [], [], ['file' => $uploadedFile]);

        /** @var UploadFileController $controller */
        $controller = static::getContainer()->get(UploadFileController::class);

        $response = $controller($request);
        $this->assertEquals(Response::HTTP_REQUEST_ENTITY_TOO_LARGE, $response->getStatusCode());

        $data = json_decode($response->getContent(), true);
        $this->assertEquals(['success' => 0], $data);
    }

    public function testFailErrorOccurred()
    {
        $client = static::createClient();

        $user = new User();
        $email = 'test@example.com';
        $user->setEmail($email);
        $user->setRoles(['ROLE_ADMIN']);
        $user->setPassword('123455');
        $this->getUserRepository()->save($user);

        $originalName = 'some-file.txt';
        [$file, $uploadedFile] = $this->createTempFile($originalName);

        $this->getEntityManager()->clear();

        $client->loginUser($user);
        // создаем реквест в ручную для того, чтобы иметь возможность эмулировать ошибку.
        $request = Request::create($this->getUrl(), Request::METHOD_POST, [], [], ['file' => $uploadedFile]);

        fclose($file); // удаляем файл, чтобы вызвать ошибку в контроллере

        /** @var UploadFileController $controller */
        $controller = static::getContainer()->get(UploadFileController::class);
        $response = $controller($request);

        $this->assertEquals(Response::HTTP_UNPROCESSABLE_ENTITY, $response->getStatusCode());

        $data = json_decode($response->getContent(), true);
        $this->assertEquals(0, $data['success']);
    }

    /**
     * @return array [resource, UploadedFile] мы возвращаем ресурс тоже, чтобы его закрыть потом можно было
     */
    private function createTempFile(string $filename): array
    {
        $content = uniqid();

        $file = tmpfile();
        fwrite($file, $content);

        $meta = stream_get_meta_data($file);
        $uploadedFile = new UploadedFile($meta['uri'], $filename, 'text/plain');

        return [$file, $uploadedFile];
    }

    private function getUrl(): string
    {
        return '/editorjs/uploadFile';
    }
}
