<?php

namespace App\Services\Service\EmailVerifier\Dto;

use Symfony\Component\Validator\Constraints as Assert;

class EmailVerifierSendDto
{
    #[Assert\NotBlank()]
    #[Assert\Email()]
    public string $email;
}
