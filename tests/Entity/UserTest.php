<?php

namespace Tests\Entity;

use App\Entity\Task;
use App\Entity\User;
use DateTime;
use PHPUnit\Framework\TestCase;

class UserTest extends TestCase
{
    public function testUserCreate(): void
    {
        $user = new User();
        $user->setUsername('User test');
        $user->setEmail('user@example.com');
        $user->setPassword('user1234');
        $task = $this->getTask();
        $user->addTask($task);

        $this->assertEquals('User test', $user->getUsername());
        $this->assertEquals('user@example.com', $user->getUserIdentifier());
        $this->assertContains('ROLE_USER', $user->getRoles());
        $tasks = $user->getTasks();
        $this->assertEquals($task, $tasks->first());
        $this->assertCount(1, $tasks);
    }

    public function testUserCreateWithRoleAdmin(): void
    {
        $user = new User();
        $user->setUsername('User test');
        $user->setEmail('user@example.com');
        $user->setPassword('user1234');
        $user->setRoles(['ROLE_ADMIN']);

        $this->assertContains('ROLE_USER', $user->getRoles());
        $this->assertContains('ROLE_ADMIN', $user->getRoles());
    }

    public function testUserRemoveTask(): void
    {
        $user = new User();
        $user->setUsername('User test');
        $user->setEmail('user@example.com');
        $user->setPassword('user1234');
        $tasks = $this->getTasks();
        $user->addTask($tasks[0]);
        $user->addTask($tasks[1]);

        $tasks = $user->getTasks();
        $this->assertCount(2, $tasks);

        $user->removeTask($tasks[1]);
        $this->assertCount(1, $user->getTasks());
    }

    private function getTask(): Task
    {
        $task = new Task();
        $task->setTitle('Task title');
        $task->setContent('Task content');
        $task->setCreatedAt(new DateTime());

        return $task;
    }

    private function getTasks(): array
    {
        $tasks = [];

        for ($i = 0; $i < 2; $i++) {
            $task = new Task();
            $task->setTitle('Task title '.$i);
            $task->setContent('Task content'.$i);
            $task->setCreatedAt(new DateTime());
            $tasks[] = $task;
        }

        return $tasks;
    }
}
