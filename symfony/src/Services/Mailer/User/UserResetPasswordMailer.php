<?php

namespace App\Services\Mailer\User;

use App\Entity\User\User;
use CoopTilleuls\ForgotPasswordBundle\Entity\AbstractPasswordToken;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Address;
use Symfony\Contracts\Translation\TranslatorInterface;

final class UserResetPasswordMailer
{
    private const API_FORGOT_PASSWORD = '/api/forgot-password';

    public function __construct(
        private readonly MailerInterface $mailer,
        private readonly TranslatorInterface $translator,
        private readonly string $domainUrl,
    ) {
    }

    public function sent(AbstractPasswordToken $passwordToken): void
    {
        /** @var User $user */
        $user = $passwordToken->getUser();

        $email = (new TemplatedEmail())
            ->to(new Address($user->getEmail(), $user->getFullName()))
            ->subject($this->translator->trans('mail.reset_password.subject'))
            ->htmlTemplate('mail/user/reset_password.html.twig')
            ->context(
                [
                    'reset_password_url' => sprintf('%s%s/%s', $this->domainUrl, self::API_FORGOT_PASSWORD, $passwordToken->getToken()),
                    'user' => $user,
                ]
            );

        $this->mailer->send($email);
    }
}
