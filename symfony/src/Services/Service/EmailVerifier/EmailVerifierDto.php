<?php

namespace App\Services\Service\EmailVerifier;

use Symfony\Component\Validator\Constraints as Assert;

class EmailVerifierDto
{
    #[Assert\NotBlank()]
    #[Assert\Email()]
    public string $email;

    #[Assert\NotBlank()]
    public string $token;

    #[Assert\NotBlank()]
    public string $expiresAt;

    #[Assert\NotBlank()]
    public string $_hash;
}
