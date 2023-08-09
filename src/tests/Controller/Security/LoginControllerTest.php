<?php

namespace App\Tests\Controller\Security;

use App\Entity\User;
use App\Tests\Traits\DependenciesTrait;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\HttpKernel\Event\RequestEvent;
use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Security\Csrf\TokenStorage\SessionTokenStorage;

class LoginControllerTest extends WebTestCase
{
    use DependenciesTrait;

    public function testSuccessfulLogin()
    {
        $client = static::createClient();

        $user = new User();
        $email = 'test@example.com';
        $user->setEmail($email);
        $user->setRoles(['ROLE_ADMIN']);
        $password = '123456';

        $hasher = $this->getPasswordHasher();
        $encodedPassword = $hasher->hashPassword($user, $password);
        $user->setPassword($encodedPassword);

        $rep = $this->getUserRepository();
        $rep->save($user);

        $this->getEntityManager()->clear();

        $csrfToken = 'a_token';
        $this->setCsrfToken($csrfToken);

        $client->request('POST', '/login', [
            '_username' => $email,
            '_password' => $password,
            '_csrf_token' => $csrfToken,
            '_target_path' => '/admin',
        ]);

        $client->followRedirect();
        $this->assertResponseIsSuccessful();
        $this->assertRouteSame('admin');

        $tokenStorage = static::getContainer()->get(TokenStorageInterface::class);
        $this->assertNotNull($tokenStorage->getToken());

        $token = $tokenStorage->getToken();
        $this->assertEquals($email, $token->getUser()->getUserIdentifier());
    }

    public function testFailedLogin()
    {
        $client = static::createClient();

        $user = new User();
        $email = 'test@example.com';
        $user->setEmail($email);
        $user->setRoles(['ROLE_ADMIN']);
        $password = '123456';

        $hasher = $this->getPasswordHasher();
        $encodedPassword = $hasher->hashPassword($user, $password);
        $user->setPassword($encodedPassword);

        $rep = $this->getUserRepository();
        $rep->save($user);

        $this->getEntityManager()->clear();

        $csrfToken = 'a_token';
        $this->setCsrfToken($csrfToken);

        $client->request('POST', '/login', [
            '_username' => $email,
            '_password' => 'other',
            '_csrf_token' => $csrfToken,
        ]);

        $client->followRedirect();
        $this->assertResponseIsSuccessful();

        $this->assertStringContainsString('Invalid credentials', $client->getCrawler()->html());

        $tokenStorage = static::getContainer()->get(TokenStorageInterface::class);
        $this->assertNull($tokenStorage->getToken());
    }

    private function setCsrfToken(string $token): void
    {
        static::getContainer()->get('event_dispatcher')->addListener(
            KernelEvents::REQUEST,
            function (RequestEvent $event) use ($token) {
                /** @var Session $session */
                $session = static::getContainer()->get('session.factory')->createSession();
                $session->set(SessionTokenStorage::SESSION_NAMESPACE.'/authenticate', $token);
                $event->getRequest()->setSession($session);
            }
        );
    }
}
