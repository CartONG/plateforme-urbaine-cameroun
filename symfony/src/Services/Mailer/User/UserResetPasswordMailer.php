<?php

namespace App\Services\Mailer\User;

use App\Entity\User\User;
use CoopTilleuls\ForgotPasswordBundle\Entity\AbstractPasswordToken;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\Mime\Address;

final class UserResetPasswordMailer extends AbstractUserMailer
{
    private const string RESET_PASSWORD_PATH = '/?dialog=reset-password&token=';

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
                    'reset_password_url' => sprintf('%s%s%s', $this->domainUrl, self::RESET_PASSWORD_PATH, $passwordToken->getToken()),
                    'user' => $user,
                ]
            );

        $this->mailer->send($email);
    }
}
