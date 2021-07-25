<?php

namespace Tests\Entity;

use App\Entity\Task;
use App\Entity\User;
use DateTime;
use PHPUnit\Framework\TestCase;

class TaskTest extends TestCase
{
    public function testTaskCreate(): void
    {
        $task = new Task();
        $task->setTitle('Test title');
        $task->setContent('Test content');
        $createdAt = $this->getDate();
        $task->setCreatedAt($createdAt);
        $user = $this->getUser();
        $task->setUser($user);

        $this->assertEquals('Test title', $task->getTitle());
        $this->assertEquals('Test content', $task->getContent());
        $this->assertEquals($createdAt, $task->getCreatedAt());
        $this->assertEquals($user, $task->getUser());
        $this->assertEquals(false, $task->isDone());
    }

    private function getDate(): DateTime
    {
        return new DateTime();
    }

    private function getUser(): User
    {
        $user = new User();
        $user->setUsername('User Test');
        $user->setPassword('user1234');
        $user->setEmail('user@example.com');

        return $user;
    }
}
