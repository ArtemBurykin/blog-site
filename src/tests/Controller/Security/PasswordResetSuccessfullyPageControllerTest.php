<?php

namespace App\Tests\Controller\Security;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Request;

class PasswordResetSuccessfullyPageControllerTest extends WebTestCase
{
    public function testSuccessful()
    {
        $client = static::createClient();

        $crawler = $client->request(Request::METHOD_GET, '/reset-password/successful');

        $this->assertResponseIsSuccessful();
        $this->assertStringContainsString(
            'Your password reset',
            $crawler->filter('title')->text()
        );
    }
}
