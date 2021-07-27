<?php

namespace Tests\Controller;

use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class DefaultControllerTest extends WebTestCase
{
    public function testIndexIsRedirectingToLogin(): void
    {
        $client = static::createClient();

        $client->request('GET', '/');

        self::assertResponseRedirects('http://localhost/login', null, 'Redirecting non logged in users to login page');

        $crawler = $client->followRedirect();

        self::assertResponseIsSuccessful();
        self::assertPageTitleSame('Welcome!');
        $button = $crawler->filter('button')->first()->text();
        self::assertSame('Se connecter', $button, 'User is redirected to login page if trying to access homepage without being connected');
    }

    public function testIndexUserLoggedIn(): void
    {
        $client = $this->createAuthenticatedClient();

        $crawler = $client->request('GET', '/');

        self::assertResponseIsSuccessful();
        self::assertPageTitleSame('Welcome!');
        $h1 = $crawler->filter('h1')->first()->text();
        self::assertSame('Bienvenue sur Todo List, l\'application vous permettant de gérer l\'ensemble de vos tâches sans effort !', $h1, 'User can access homepage when logged in');
    }

    /**
     * @return KernelBrowser
     */
    private function createAuthenticatedClient(): KernelBrowser
    {
        $client = static::createClient();
        $userRepository = self::getContainer()->get(UserRepository::class);

        $user = $userRepository->findOneBy(['email' => 'admin@example.com']);

        $client->loginUser($user);

        return $client;
    }
}
