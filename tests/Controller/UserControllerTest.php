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
    public const USER_TEST_PASSWORD = 'usertest1234';
    public const USER_TEST_USERNAME = 'User test';
    public const USER_TEST_EMAIL = 'test@example.com';
    public const ADMIN_EMAIL = 'admin@example.com';
    public const USER_EMAIL = 'user@example.com';
    public const USER_PASSWORD = 'user1234';
    public const ADD_USER_BUTTON = 'Ajouter';
    public const EDIT_USER_BUTTON = 'Modifier';

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

        $password = self::USER_TEST_PASSWORD;
        $client->submitForm(self::ADD_USER_BUTTON, [
            'user[username]' => self::USER_TEST_USERNAME,
            'user[password][first]' => $password,
            'user[password][second]' => $password,
            'user[email]' => self::USER_TEST_EMAIL,
        ]);

        $crawler = $client->followRedirect();

        self::assertResponseIsSuccessful();
        self::assertRouteSame('user_list');
        self::assertStringContainsString('<strong>Superbe !</strong> L&#039;utilisateur a bien été ajouté.', $client->getResponse()->getContent(), 'User is created successfully, admin is redirected to Users list page, flash message is visible');
        self::assertCount(29, $crawler->filter('tr'), 'New User is added to the Users list');
    }

    public function testCreateUserAlreadyExists(): void
    {
        $client = $this->createAuthenticatedClient();

        $client->request('GET', self::USERS_CREATE);

        $password = self::USER_TEST_PASSWORD;
        $client->submitForm(self::ADD_USER_BUTTON, [
            'user[username]' => self::USER_TEST_USERNAME,
            'user[password][first]' => $password,
            'user[password][second]' => $password,
            'user[email]' => self::USER_EMAIL,
        ]);

        self::assertRouteSame('user_create');
        self::assertStringContainsString('This value is already used.', $client->getResponse()->getContent(), 'User email already exists in database, an error message is shown');
    }

    public function testCreateUserInvalidUserName(): void
    {
        $client = $this->createAuthenticatedClient();

        $client->request('GET', self::USERS_CREATE);

        $password = self::USER_TEST_PASSWORD;
        $client->submitForm(self::ADD_USER_BUTTON, [
            'user[username]' => '',
            'user[password][first]' => $password,
            'user[password][second]' => $password,
            'user[email]' => self::USER_TEST_EMAIL,
        ]);

        self::assertRouteSame('user_create');
        self::assertStringContainsString('Vous devez saisir un nom d&#039;utilisateur.', $client->getResponse()->getContent(), 'When username is empty, an error message is shown');
    }

    public function testCreateUserEmptyEmail(): void
    {
        $client = $this->createAuthenticatedClient();

        $client->request('GET', self::USERS_CREATE);

        $password = self::USER_TEST_PASSWORD;
        $client->submitForm(self::ADD_USER_BUTTON, [
            'user[username]' => self::USER_TEST_USERNAME,
            'user[password][first]' => $password,
            'user[password][second]' => $password,
            'user[email]' => '',
        ]);

        self::assertRouteSame('user_create');
        self::assertStringContainsString('Vous devez saisir une adresse email.', $client->getResponse()->getContent(), 'When email is empty, an error message is shown');
    }

    public function testCreateUserInvalidEmail(): void
    {
        $client = $this->createAuthenticatedClient();

        $client->request('GET', self::USERS_CREATE);

        $password = self::USER_TEST_PASSWORD;
        $client->submitForm(self::ADD_USER_BUTTON, [
            'user[username]' => self::USER_TEST_USERNAME,
            'user[password][first]' => $password,
            'user[password][second]' => $password,
            'user[email]' => 'test',
        ]);

        self::assertRouteSame('user_create');
        self::assertStringContainsString('Le format de l&#039;adresse n&#039;est pas correct.', $client->getResponse()->getContent(), 'When email is invalid, an error message is shown');
    }

    public function testCreateUserEmptyPassword(): void
    {
        $client = $this->createAuthenticatedClient();

        $client->request('GET', self::USERS_CREATE);

        $client->submitForm(self::ADD_USER_BUTTON, [
            'user[username]' => self::USER_TEST_USERNAME,
            'user[password][first]' => '',
            'user[password][second]' => '',
            'user[email]' => self::USER_TEST_EMAIL,
        ]);

        self::assertRouteSame('user_create');
        self::assertStringContainsString('Vous devez saisir un mot de passe.', $client->getResponse()->getContent(), 'When email is invalid, an error message is shown');
    }

    public function testEditUserAlreadyExists(): void
    {
        $client = $this->createAuthenticatedClient();

        $client->request('GET', self::USERS_EDIT);

        $password = self::USER_PASSWORD;
        $client->submitForm(self::EDIT_USER_BUTTON, [
            'user[username]' => self::USER_TEST_USERNAME,
            'user[password][first]' => $password,
            'user[password][second]' => $password,
            'user[email]' => self::ADMIN_EMAIL,
        ]);

        self::assertRouteSame('user_edit');
        self::assertStringContainsString('This value is already used.', $client->getResponse()->getContent(), 'User email already exists in database, an error message is shown');
    }

    public function testAdminCanEditUser(): void
    {
        $client = $this->createAuthenticatedClient();

        $client->request('GET', self::USERS_EDIT);

        $password = self::USER_TEST_PASSWORD;
        $client->submitForm(self::EDIT_USER_BUTTON, [
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

        $password = self::USER_PASSWORD;
        $form = $crawler->selectButton(self::EDIT_USER_BUTTON)->form();
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
            $user = $userRepository->findOneBy(['email' => self::USER_EMAIL]);
        } else {
            $user = $userRepository->findOneBy(['email' => self::ADMIN_EMAIL]);
        }

        $client->loginUser($user);

        return $client;
    }
}
