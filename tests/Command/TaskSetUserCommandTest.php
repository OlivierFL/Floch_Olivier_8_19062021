<?php

namespace Tests\Command;

use Symfony\Bundle\FrameworkBundle\Console\Application;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\Console\Tester\CommandTester;

class TaskSetUserCommandTest extends KernelTestCase
{
    public function testExecute(): void
    {
        $kernel = static::createKernel();
        $app = new Application($kernel);

        $command = $app->find('task:set-user');

        $commandTester = new CommandTester($command);
        $commandTester->execute([]);
        $output = $commandTester->getDisplay();

        $this->assertStringContainsString('All Tasks have been updated', $output);
    }

    public function testExecuteNoTasksToUpdate(): void
    {
        $kernel = static::createKernel();
        $app = new Application($kernel);

        $command = $app->find('task:set-user');

        $commandTester = new CommandTester($command);
        $commandTester->execute([]);
        $commandTester->execute([]);
        $output = $commandTester->getDisplay();

        $this->assertStringContainsString('No Tasks without User have been found', $output);
    }

    public function testExecuteNoAnonymousUser(): void
    {
        $kernel = static::createKernel();
        $app = new Application($kernel);

        $command = $app->find('task:set-user');

        $commandTester = new CommandTester($command);
        $commandTester->execute([]);
        $output = $commandTester->getDisplay();

        $this->assertStringContainsString('No anonymous User found, creating new anonymous User', $output);
    }
}
