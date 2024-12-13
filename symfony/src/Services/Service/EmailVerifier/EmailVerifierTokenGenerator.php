<?php

namespace App\Services\Service\EmailVerifier;

class EmailVerifierTokenGenerator
{
    public function __construct(#[\SensitiveParameter] private readonly string $secret)
    {
    }

    /**
     * Get a cryptographically secure token.
     *
     * @throws \JsonException
     */
    public function createToken(string $userId, string $email): string
    {
        $encodedData = json_encode([$userId, $email], JSON_THROW_ON_ERROR);

        return base64_encode(hash_hmac('sha256', $encodedData, $this->secret, true));
    }
}
