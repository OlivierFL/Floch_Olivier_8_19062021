<?php

namespace App\Command;

use App\Entity\User;
use App\Repository\TaskRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Helper\ProgressBar;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

#[AsCommand(
    name: 'task:set-user',
    description: 'Add anonymous user to existing Tasks without User',
)]
class TaskSetUserCommand extends Command
{
    /**
     * TaskSetUserCommand constructor.
     */
    public function __construct(private TaskRepository $repository, private EntityManagerInterface $entityManager, private UserPasswordHasherInterface $passwordHasher)
    {
        parent::__construct();
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);
        $progressBar = new ProgressBar($output);

        $tasksWithoutUser = $this->repository->findBy(['user' => null]);

        if (!$tasksWithoutUser) {
            $io->info('No Tasks without User have been found');

            return Command::SUCCESS;
        }

        $anonymousUser = (new User())
            ->setUsername('anonyme')
            ->setEmail('anonyme@example.com')
        ;

        $anonymousUser->setPassword($this->passwordHasher->hashPassword($anonymousUser, 'anonyme1234'));

        $this->entityManager->persist($anonymousUser);

        foreach ($progressBar->iterate($tasksWithoutUser) as $task) {
            $task->setUser($anonymousUser);
            $this->entityManager->persist($task);
        }

        $this->entityManager->flush();

        $io->success('All Tasks have been updated');

        return Command::SUCCESS;
    }
}
