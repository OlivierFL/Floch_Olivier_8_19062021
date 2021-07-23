<?php

namespace Tests\Controller;

use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class UserControllerTest extends WebTestCase
{
    public const USERS_LIST = '/users';
    public const USERS_CREATE = '/users/create';
    public const USERS_EDIT = '/users/2/edit';

    public function testAdminCanAccessUsersList(): void
    {
        $client = $this->createAuthenticatedClient();

        $crawler = $client->request('GET', self::USERS_LIST);
        self::assertResponseIsSuccessful();
        self::assertPageTitleSame('Welcome!');
        $h1 = $crawler->filter('h1')->first()->text();
        self::assertSame('Liste des utilisateurs', $h1, 'Admin can access users list');
    }

    public function testUserCanNotAccessUsersList(): void
    {
        $client = $this->createAuthenticatedClient(false);

        $client->request('GET', self::USERS_LIST);

        self::assertResponseStatusCodeSame(403, 'User can not access users list');
    }

    public function testAdminCanCreateUser(): void
    {
        $client = $this->createAuthenticatedClient();

        $client->request('GET', self::USERS_CREATE);

        $password = 'usertest1234';
        $client->submitForm('Ajouter', [
            'user[username]' => 'Test User',
            'user[password][first]' => $password,
            'user[password][second]' => $password,
            'user[email]' => 'test@example.com',
        ]);

        $crawler = $client->followRedirect();

        self::assertResponseIsSuccessful();
        self::assertRouteSame('user_list');
        self::assertStringContainsString('<strong>Superbe !</strong> L&#039;utilisateur a bien été ajouté.', $client->getResponse()->getContent(), 'User is created successfully, admin is redirected to Users list page, flash message is visible');
        self::assertCount(29, $crawler->filter('tr'), 'New User is added to the Users list');
    }

    public function testAdminCanEditUser(): void
    {
        $client = $this->createAuthenticatedClient();

        $client->request('GET', self::USERS_EDIT);

        $password = 'usertest1234';
        $client->submitForm('Modifier', [
            'user[username]' => 'Test User edited',
            'user[password][first]' => $password,
            'user[password][second]' => $password,
            'user[email]' => 'test-edited@example.com',
        ]);

        $client->followRedirect();

        self::assertResponseIsSuccessful();
        self::assertRouteSame('user_list');
        self::assertStringContainsString('<strong>Superbe !</strong> L&#039;utilisateur a bien été modifié.', $client->getResponse()->getContent(), 'User is edited successfully, admin is redirected to Users list page, flash message is visible');
    }

    public function testAdminCanEditUserRole(): void
    {
        $client = $this->createAuthenticatedClient();

        $crawler = $client->request('GET', self::USERS_EDIT);

        $password = 'user1234';
        $form = $crawler->selectButton('Modifier')->form();
        $form->setValues([
            'user[password][first]' => $password,
            'user[password][second]' => $password,
        ]);

        $form['user[roles]']->select('ROLE_ADMIN');

        $client->submit($form);

        $client->followRedirect();

        self::assertResponseIsSuccessful();
        self::assertRouteSame('user_list');
        self::assertStringContainsString('<strong>Superbe !</strong> L&#039;utilisateur a bien été modifié.', $client->getResponse()->getContent(), 'User role is edited successfully, admin is redirected to Users list page, flash message is visible');
    }

    /**
     * @param bool $isAdmin
     *
     * @return KernelBrowser
     */
    private function createAuthenticatedClient(bool $isAdmin = true): KernelBrowser
    {
        $client = static::createClient();
        $userRepository = self::getContainer()->get(UserRepository::class);

        if (!$isAdmin) {
            $user = $userRepository->findOneBy(['email' => 'user@example.com']);
        } else {
            $user = $userRepository->findOneBy(['email' => 'admin@example.com']);
        }

        $client->loginUser($user);

        return $client;
    }
}
