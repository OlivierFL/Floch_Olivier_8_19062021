<?php

namespace App\Security\Voter;

use App\Entity\Task;
use App\Entity\User;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\Security\Core\User\UserInterface;

class TaskVoter extends Voter
{
    public const TASK_DELETE = 'task_delete';

    /**
     * TaskVoter constructor.
     *
     * @param Security $security
     */
    public function __construct(private Security $security)
    {
    }

    /**
     * @param mixed $subject
     */
    protected function supports(string $attribute, $subject): bool
    {
        return self::TASK_DELETE === $attribute && $subject instanceof Task;
    }

    /**
     * @param mixed $subject
     */
    protected function voteOnAttribute(string $attribute, $subject, TokenInterface $token): bool
    {
        $user = $token->getUser();

        if (!$user instanceof UserInterface) {
            return false;
        }

        /** @var Task $task */
        $task = $subject;

        if ($this->security->isGranted('ROLE_ADMIN') && User::ANONYMOUS_USER_EMAIL === $task->getUser()->getUserIdentifier()) {
            return true;
        }

        return $task->getUser() === $user;
    }
}
