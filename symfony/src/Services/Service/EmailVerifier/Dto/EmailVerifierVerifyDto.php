<?php

namespace App\Services\Service\EmailVerifier\Dto;

use Symfony\Component\Validator\Constraints as Assert;

class EmailVerifierVerifyDto extends EmailVerifierSendDto
{
    #[Assert\NotBlank()]
    public string $token;

    #[Assert\NotBlank()]
    public string $expiresAt;

    #[Assert\NotBlank()]
    public string $_hash;
}
