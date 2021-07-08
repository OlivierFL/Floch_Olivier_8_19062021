<?php

namespace App\Doctrine;

use App\Entity\Task;
use Symfony\Component\Security\Core\Security;

class TaskSetUserListener
{
    /**
     * TaskSetUserListener constructor.
     */
    public function __construct(private Security $security)
    {
    }

    public function prePersist(Task $task): void
    {
        if ($task->getUser()) {
            return;
        }

        $user = $this->security->getUser();

        if ($user) {
            $task->setUser($user);
        }
    }
}
