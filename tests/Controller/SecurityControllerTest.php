<?php

namespace Tests\Controller;

use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class SecurityControllerTest extends WebTestCase
{
    public function testLogin(): void
    {
        $client = self::createClient();

        $client->request('GET', '/login');

        $client->submitForm('Se connecter', [
            '_username' => 'user@example.com',
            '_password' => 'user1234',
        ]);

        $client->followRedirect();

        self::assertResponseIsSuccessful();
        self::assertRouteSame('homepage');
    }

    public function testLoginEmptyUserName(): void
    {
        $client = self::createClient();

        $client->request('GET', '/login');

        $client->submitForm('Se connecter', [
            '_username' => '',
            '_password' => 'user1234',
        ]);

        $client->followRedirect();

        self::assertRouteSame('login');
        self::assertStringContainsString('Invalid credentials.', $client->getResponse()->getContent(), 'When logging with empty username, an error message is shown');
    }

    public function testLoginEmptyUserPassword(): void
    {
        $client = self::createClient();

        $client->request('GET', '/login');

        $client->submitForm('Se connecter', [
            '_username' => 'user@example.com',
            '_password' => '',
        ]);

        $client->followRedirect();

        self::assertRouteSame('login');
        self::assertStringContainsString('Invalid credentials.', $client->getResponse()->getContent(), 'When logging with empty password, an error message is shown');
    }

    public function testLoginInvalidCredentials(): void
    {
        $client = self::createClient();

        $client->request('GET', '/login');

        $client->submitForm('Se connecter', [
            '_username' => 'user',
            '_password' => 'test1234',
        ]);

        $client->followRedirect();

        self::assertRouteSame('login');
        self::assertStringContainsString('Invalid credentials.', $client->getResponse()->getContent(), 'When logging with invalid credentials, an error message is shown');
    }

    public function testLogout(): void
    {
        $client = $this->createAuthenticatedClient();

        $client->request('GET', '/logout');

        self::assertResponseIsSuccessful();
        self::assertRouteSame('login', [], 'When logging out, user is redirected to login page');
    }

    /**
     * @return KernelBrowser
     */
    private function createAuthenticatedClient(): KernelBrowser
    {
        $client = static::createClient();
        $client->followRedirects();
        $userRepository = self::getContainer()->get(UserRepository::class);

        $user = $userRepository->findOneBy(['email' => 'admin@example.com']);

        $client->loginUser($user);

        return $client;
    }
}
