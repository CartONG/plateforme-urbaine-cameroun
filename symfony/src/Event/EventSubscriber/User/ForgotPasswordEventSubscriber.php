<?php

namespace App\Event\EventSubscriber\User;

use App\Entity\User\User;
use App\Security\PasswordHasher;
use App\Services\Mailer\User\UserResetPasswordMailer;
use CoopTilleuls\ForgotPasswordBundle\Event\CreateTokenEvent;
use CoopTilleuls\ForgotPasswordBundle\Event\UpdatePasswordEvent;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

final readonly class ForgotPasswordEventSubscriber implements EventSubscriberInterface
{
    public function __construct(
        private EntityManagerInterface $entityManager,
        private PasswordHasher $passwordHasher,
        private UserResetPasswordMailer $userResetPasswordMailer,
    ) {
    }

    public static function getSubscribedEvents(): array
    {
        return [
            CreateTokenEvent::class => 'onCreateToken',
            UpdatePasswordEvent::class => 'onUpdatePassword',
        ];
    }

    public function onCreateToken(CreateTokenEvent $event): void
    {
        $this->userResetPasswordMailer->sent($event->getPasswordToken());
    }

    public function onUpdatePassword(UpdatePasswordEvent $event): void
    {
        $passwordToken = $event->getPasswordToken();
        /** @var User $user */
        $user = $passwordToken->getUser();
        $user->setPlainPassword($event->getPassword());
        $this->passwordHasher->hashPassword($user);
        $this->entityManager->persist($user);
        $this->entityManager->flush();
    }
}
