<?php

namespace App\Services\Mailer\User;

use Symfony\Component\Mailer\MailerInterface;
use Symfony\Contracts\Translation\TranslatorInterface;

abstract class AbstractUserMailer
{
    public function __construct(
        protected readonly MailerInterface $mailer,
        protected readonly TranslatorInterface $translator,
        protected readonly string $domainUrl,
    ) {
    }
}
