<?php

namespace Tests\Controller;

use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class TaskControllerTest extends WebTestCase
{
    public const TASKS_LIST = '/tasks';
    public const TASKS_CREATE = '/tasks/create';
    public const TASKS_EDIT = '/tasks/1/edit';
    public const TASKS_TOGGLE = '/tasks/1/toggle';
    public const TASKS_DELETE = '/tasks/1/delete';
    public const TASKS_DELETE_DENIED = '/tasks/37/delete';
    public const TASKS_DELETE_ANONYMOUS = '/tasks/2/delete';

    public function testTasksList(): void
    {
        $client = $this->createAuthenticatedClient();

        $crawler = $client->request('GET', self::TASKS_LIST);
        self::assertResponseIsSuccessful();
        self::assertPageTitleSame('Welcome!');
        self::assertCount(37, $crawler->filter('.thumbnail'), 'Tasks list is accessible to logged in users, and has 37 tasks by default');
    }

    public function testTasksListUserNotLoggedIn(): void
    {
        $client = static::createClient();

        $client->request('GET', self::TASKS_LIST);

        self::assertResponseRedirects('http://localhost/login', null, 'Redirecting non logged in users to login page');

        $crawler = $client->followRedirect();

        self::assertResponseIsSuccessful();
        self::assertPageTitleSame('Welcome!');
        $button = $crawler->filter('button')->first()->text();
        self::assertSame('Se connecter', $button, 'User is redirected to login page if trying to access homepage without being connected');
    }

    public function testTaskEditPage(): void
    {
        $client = $this->createAuthenticatedClient();

        $crawler = $client->request('GET', self::TASKS_EDIT);
        self::assertResponseIsSuccessful();
        self::assertPageTitleSame('Welcome!');
        $button = $crawler->filter('button')->first()->text();
        self::assertSame('Modifier', $button, 'Logged in User can access Task edition page');
    }

    public function testTaskEditPageUserNotLoggedIn(): void
    {
        $client = static::createClient();

        $client->request('GET', self::TASKS_EDIT);

        self::assertResponseRedirects('http://localhost/login', null, 'Redirecting non logged in users to login page');

        $crawler = $client->followRedirect();

        self::assertResponseIsSuccessful();
        self::assertPageTitleSame('Welcome!');
        $button = $crawler->filter('button')->first()->text();
        self::assertSame('Se connecter', $button, 'User is redirected to login page if trying to access homepage without being connected');
    }

    public function testUserCanCreateTask(): void
    {
        $client = $this->createAuthenticatedClient();

        $client->request('GET', self::TASKS_CREATE);

        $client->submitForm('Ajouter', [
            'task[title]' => 'New task title',
            'task[content]' => 'New task content',
        ]);

        $crawler = $client->followRedirect();

        self::assertResponseIsSuccessful();
        self::assertRouteSame('task_list');
        self::assertStringContainsString('<strong>Superbe !</strong> La tâche a bien été ajoutée.', $client->getResponse()->getContent(), 'Task is created successfully, user is redirected to tasks list page, flash message is visible');
        self::assertCount(38, $crawler->filter('.thumbnail'), 'New Task is added to the Tasks list');
    }

    public function testUserCanEditTask(): void
    {
        $client = $this->createAuthenticatedClient();

        $client->request('GET', self::TASKS_EDIT);

        $client->submitForm('Modifier', [
            'task[title]' => 'Edited task title',
            'task[content]' => 'Edited task content',
        ]);

        $client->followRedirect();

        self::assertResponseIsSuccessful();
        self::assertRouteSame('task_list');
        self::assertStringContainsString('<strong>Superbe !</strong> La tâche a bien été modifiée.', $client->getResponse()->getContent(), 'Task is edited successfully, user is redirected to tasks list page, flash message is visible');
    }

    public function testUserCanToggleTaskStatus(): void
    {
        $client = $this->createAuthenticatedClient();

        $client->request('GET', self::TASKS_TOGGLE);

        $client->followRedirect();

        self::assertResponseIsSuccessful();
        self::assertRouteSame('task_list');
        self::assertStringContainsString('<strong>Superbe !</strong> La tâche Titre Tâche de test a bien été marquée comme faite.', $client->getResponse()->getContent(), 'Task is marked as done, user is redirected to tasks list page, flash message is visible');
    }

    public function testUserCanDeleteOwnTask(): void
    {
        $client = $this->createAuthenticatedClient();

        $client->request('GET', self::TASKS_DELETE);

        $client->followRedirect();

        self::assertResponseIsSuccessful();
        self::assertRouteSame('task_list');
        self::assertStringContainsString('<strong>Superbe !</strong> La tâche a bien été supprimée.', $client->getResponse()->getContent(), 'Task is deleted, user is redirected to tasks list page, flash message is visible');
    }

    public function testUserCanNotDeleteOtherUserTask(): void
    {
        $client = $this->createAuthenticatedClient();

        $client->request('GET', self::TASKS_DELETE_DENIED);

        self::assertResponseStatusCodeSame(403, 'User can not delete other User\'s Task');
    }

    public function testAdminCanDeleteAnonymousUserTask(): void
    {
        $client = $this->createAuthenticatedClient();

        $client->request('GET', self::TASKS_DELETE_ANONYMOUS);

        $client->followRedirect();

        self::assertResponseIsSuccessful();
        self::assertRouteSame('task_list');
        self::assertStringContainsString('<strong>Superbe !</strong> La tâche a bien été supprimée.', $client->getResponse()->getContent(), 'Task is deleted, admin is redirected to tasks list page, flash message is visible');
    }

    public function testUserCanNotDeleteAnonymousUserTask(): void
    {
        $client = $this->createAuthenticatedClient(false);

        $client->request('GET', self::TASKS_DELETE_ANONYMOUS);

        self::assertResponseStatusCodeSame(403, 'User can not delete other User\'s Task');

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
