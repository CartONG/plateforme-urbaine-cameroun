<?php

namespace App\Services\Mailer\User;

use App\Entity\User\User;
use CoopTilleuls\ForgotPasswordBundle\Entity\AbstractPasswordToken;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Address;

final class UserResetPasswordMailer
{
    private const API_FORGOT_PASSWORD = '/api/forgot-password';

    public function __construct(private readonly MailerInterface $mailer, private readonly string $domainUrl)
    {
    }

    public function sent(AbstractPasswordToken $passwordToken): void
    {
        /** @var User $user */
        $user = $passwordToken->getUser();

        $email = (new TemplatedEmail())
            ->to(new Address($user->getEmail(), $user->getFullName()))
            ->subject('Reset your password')
            ->htmlTemplate('mail/user/reset_password.html.twig')
            ->context(
                [
                    'reset_password_url' => sprintf('%s%s/%s', $this->domainUrl, self::API_FORGOT_PASSWORD, $passwordToken->getToken()),
                ]
            );

        $this->mailer->send($email);
    }
}
