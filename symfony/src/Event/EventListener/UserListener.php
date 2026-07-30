<?php

namespace App\Event\EventListener;

use App\Entity\User\User;
use App\Security\PasswordHasher;
use App\Services\Mailer\User\UserEmailVerifierMailer;
use Doctrine\Bundle\DoctrineBundle\Attribute\AsEntityListener;
use Doctrine\ORM\Events;
use Doctrine\Persistence\Event\LifecycleEventArgs;

#[AsEntityListener(event: Events::prePersist, method: 'hashPassword', entity: User::class)]
#[AsEntityListener(event: Events::postPersist, method: 'postPersist', entity: User::class)]
#[AsEntityListener(event: Events::preUpdate, method: 'hashPassword', entity: User::class)]
class UserListener
{
    public function __construct(
        private readonly PasswordHasher $passwordHasher,
        private readonly UserEmailVerifierMailer $userEmailVerifierMailer,
    ) {
    }

    public function postPersist(User $user, LifecycleEventArgs $event): void
    {
        if ($user->getIsValidated()) {
            return;
        }

        $this->userEmailVerifierMailer->send($user);
    }

    public function hashPassword(User $user, LifecycleEventArgs $event): void
    {
        $this->passwordHasher->hashPassword($user);
    }
}
