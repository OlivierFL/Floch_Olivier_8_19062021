<?php

namespace Tests\Controller;

use App\Entity\User;
use App\Repository\TaskRepository;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class TaskControllerTest extends WebTestCase
{
    public const TASKS_LIST = '/tasks';
    public const TASKS_CREATE = '/tasks/create';
    public const TASKS_EDIT = '/tasks/1/edit';
    public const TASKS_TOGGLE = '/tasks/1/toggle';
    public const TASKS_DELETE = '/tasks/1/delete';
    public const TASKS_DELETE_DENIED = '/tasks/36/delete';
    public const TASKS_DELETE_ANONYMOUS = '/tasks/2/delete';
    public const ADD_TASK_BUTTON = 'Ajouter';
    public const EDIT_TASK_BUTTON = 'Modifier';

    public function testTasksList(): void
    {
        $client = $this->createAuthenticatedClient();

        $crawler = $client->request('GET', self::TASKS_LIST);
        self::assertResponseIsSuccessful();
        self::assertPageTitleSame('Welcome!');
        self::assertCount(36, $crawler->filter('.thumbnail'), 'Tasks list is accessible to logged in users, and has 36 tasks by default');
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

        $client->submitForm(self::ADD_TASK_BUTTON, [
            'task[title]' => 'New task title',
            'task[content]' => 'New task content',
        ]);

        $crawler = $client->followRedirect();

        self::assertResponseIsSuccessful();
        self::assertRouteSame('task_list');
        self::assertStringContainsString('<strong>Superbe !</strong> La tâche a bien été ajoutée.', $client->getResponse()->getContent(), 'Task is created successfully, user is redirected to tasks list page, flash message is visible');
        self::assertCount(37, $crawler->filter('.thumbnail'), 'New Task is added to the Tasks list');
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

    public function testCreateTaskEmptyTitle(): void
    {
        $client = $this->createAuthenticatedClient();

        $client->request('GET', self::TASKS_CREATE);

        $client->submitForm(self::ADD_TASK_BUTTON, [
            'task[title]' => '',
            'task[content]' => 'New task content',
        ]);

        self::assertRouteSame('task_create');
        self::assertStringContainsString('Vous devez saisir un titre.', $client->getResponse()->getContent(), 'When task title is empty, an error message is shown');
    }

    public function testCreateTaskEmptyContent(): void
    {
        $client = $this->createAuthenticatedClient();

        $client->request('GET', self::TASKS_CREATE);

        $client->submitForm(self::ADD_TASK_BUTTON, [
            'task[title]' => 'New task title',
            'task[content]' => '',
        ]);

        self::assertRouteSame('task_create');
        self::assertStringContainsString('Vous devez saisir du contenu.', $client->getResponse()->getContent(), 'When task content is empty, an error message is shown');
    }

    public function testEditTaskEmptyTitle(): void
    {
        $client = $this->createAuthenticatedClient();

        $client->request('GET', self::TASKS_EDIT);

        $client->submitForm(self::EDIT_TASK_BUTTON, [
            'task[title]' => '',
            'task[content]' => 'Edited task content',
        ]);

        self::assertRouteSame('task_edit');
        self::assertStringContainsString('Vous devez saisir un titre.', $client->getResponse()->getContent(), 'When task title is empty, an error message is shown');
    }

    public function testEditTaskEmptyContent(): void
    {
        $client = $this->createAuthenticatedClient();

        $client->request('GET', self::TASKS_EDIT);

        $client->submitForm(self::EDIT_TASK_BUTTON, [
            'task[title]' => 'Edited task title',
            'task[content]' => '',
        ]);

        self::assertRouteSame('task_edit');
        self::assertStringContainsString('Vous devez saisir du contenu.', $client->getResponse()->getContent(), 'When task content is empty, an error message is shown');
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

    /**
     * @throws OptimisticLockException
     * @throws ORMException
     */
    public function testAdminCanDeleteAnonymousUserTask(): void
    {
        $client = $this->createAuthenticatedClient();
        $anonymousUser = $this->createAnonymousUser();
        $this->linkAnonymousUserToTask($anonymousUser);

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

    /**
     * @throws OptimisticLockException
     * @throws ORMException
     */
    private function createAnonymousUser(): User
    {
        $passwordHasher = self::getContainer()->get(UserPasswordHasherInterface::class);

        $anonymousUser = (new User())
            ->setUsername('anonyme')
            ->setEmail(User::ANONYMOUS_USER_EMAIL)
        ;
        $anonymousUser->setPassword($passwordHasher->hashPassword($anonymousUser, 'anonyme1234'));

        $manager = self::getContainer()->get(EntityManagerInterface::class);
        $manager->persist($anonymousUser);

        return $anonymousUser;
    }

    /**
     * @throws OptimisticLockException
     * @throws ORMException
     */
    private function linkAnonymousUserToTask(User $user, int $taskId = 2): void
    {
        $taskRepository = self::getContainer()->get(TaskRepository::class);
        $task = $taskRepository->findOneBy(['id' => $taskId]);
        $task->setUser($user);
        $manager = self::getContainer()->get(EntityManagerInterface::class);
        $manager->persist($task);
    }
}
