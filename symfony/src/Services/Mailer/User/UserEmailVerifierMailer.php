<?php

namespace App\Services\Mailer\User;

use App\Entity\User\User;
use App\Services\Service\EmailVerifier\EmailVerifierService;
use App\Services\Service\EmailVerifier\Exception\SignatureParamsException;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\Mailer\Exception\TransportExceptionInterface;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Address;
use Symfony\Contracts\Translation\TranslatorInterface;

final class UserEmailVerifierMailer extends AbstractUserMailer
{
    public const string VERIFY_EMAIL_DIALOG = '&dialog=verify-email';

    public function __construct(
        MailerInterface $mailer,
        TranslatorInterface $translator,
        string $domainUrl,
        private readonly EmailVerifierService $verifyEmail,
    ) {
        parent::__construct($mailer, $translator, $domainUrl);
    }

    /**
     * @throws SignatureParamsException
     * @throws TransportExceptionInterface
     * @throws \JsonException
     */
    public function send(User $user): void
    {
        $signature = $this->verifyEmail->getSignature();

        $email = (new TemplatedEmail())
            ->to(new Address($user->getEmail(), $user->getFullName()))
            ->subject($this->translator->trans('mail.verify_email.subject'))
            ->htmlTemplate('mail/user/verify_email.html.twig')
            ->context(
                [
                    'verify_email_url' => sprintf('%s/%s&%s', $this->domainUrl, $signature->generateQueryParams($user), self::VERIFY_EMAIL_DIALOG),
                    'user' => $user,
                ]
            );

        $this->mailer->send($email);
    }
}
