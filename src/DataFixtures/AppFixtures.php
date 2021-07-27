<?php

namespace App\DataFixtures;

use App\Entity\Task;
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;
use Faker\Generator;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class AppFixtures extends Fixture
{
    private const TIMEZONE = 'Europe/Paris';
    private Generator $faker;

    private array $users = [];
    private User $admin;

    public function __construct(private UserPasswordHasherInterface $passwordHasher)
    {
        $this->faker = Factory::create('fr');
    }

    public function load(ObjectManager $manager): void
    {
        $this->loadAdmin($manager);
        $this->loadUsers($manager);
        $this->loadTasks($manager);

        $manager->flush();
    }

    private function loadTasks(ObjectManager $manager): void
    {
        // Generating Task for tests
        $testTask = new Task();
        $testTask->setTitle('Titre Tâche de test');
        $testTask->setContent('Contenu Tâche de test');
        $testTask->setCreatedAt($this->faker->dateTime('now', self::TIMEZONE));
        $testTask->setUser($this->admin);
        $manager->persist($testTask);

        // Generating 10 tasks without User
        for ($i = 0; $i < 10; ++$i) {
            $task = new Task();
            $task->setTitle($this->faker->text());
            $task->setContent($this->faker->sentences(1, true));
            $task->setCreatedAt($this->faker->dateTime('now', self::TIMEZONE));
            $task->setUser(null);
            $manager->persist($task);
        }

        // Generating 25 tasks with User
        for ($i = 0; $i < 25; ++$i) {
            $task = new Task();
            $task->setTitle($this->faker->text());
            $task->setContent($this->faker->sentences(1, true));
            $task->setCreatedAt($this->faker->dateTime('now', self::TIMEZONE));
            $task->setUser($this->getRandomUser());
            $manager->persist($task);
        }
    }

    private function loadUsers(ObjectManager $manager): void
    {
        for ($i = 0; $i < 25; ++$i) {
            $user = new User();
            if (0 === $i) {
                $user->setUsername('user');
                $user->setEmail('user@example.com');
                $user->setPassword($this->passwordHasher->hashPassword($user, 'user1234'));
            } else {
                $user->setUsername($this->faker->userName);
                $user->setEmail($this->faker->email);
                $user->setPassword($this->passwordHasher->hashPassword($user, $this->faker->password(1, 64)));
            }
            $this->users[] = $user;
            $manager->persist($user);
        }
    }

    private function loadAdmin(ObjectManager $manager): void
    {
        $admin = new User();
        $admin->setUsername('admin');
        $admin->setEmail('admin@example.com');
        $admin->setPassword($this->passwordHasher->hashPassword($admin, 'admin1234'));
        $admin->setRoles(['ROLE_ADMIN']);
        $manager->persist($admin);
        $this->admin = $admin;
    }

    private function getRandomUser(): User
    {
        return $this->users[array_rand($this->users)];
    }
}
