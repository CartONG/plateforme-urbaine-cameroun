<?php

namespace App\Event\EventSubscriber\User;

use App\Entity\User\User;
use App\Services\Mailer\User\UserResetPasswordMailer;
use CoopTilleuls\ForgotPasswordBundle\Event\CreateTokenEvent;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Address;

final readonly class ForgotPasswordEventSubscriber implements EventSubscriberInterface
{
    public function __construct(private UserResetPasswordMailer $userResetPasswordMailer)
    {
    }

    public static function getSubscribedEvents(): array
    {
        return [
            CreateTokenEvent::class => 'onCreateToken',
        ];
    }

    public function onCreateToken(CreateTokenEvent $event): void
    {
        $this->userResetPasswordMailer->sent($event->getPasswordToken());
    }
}
