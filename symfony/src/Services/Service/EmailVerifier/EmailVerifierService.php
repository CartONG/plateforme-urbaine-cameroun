<?php

namespace App\Services\Service\EmailVerifier;

use App\Entity\User\User;

class EmailVerifierService
{
    public function __construct(
        private readonly EmailVerifierTokenGenerator $tokenGenerator,
        private readonly EmailVerifierSignature $signature,
        private readonly int $lifetime,
    ) {
    }

    /**
     * @throws \JsonException
     */
    public function getSignature(User $user): EmailVerifierSignature
    {
        $this->signature->set(
            $this->createToken($user),
            $this->getExpiryTime(),
            $user->getEmail()
        );

        return $this->signature;
    }

    /**
     * @throws \JsonException
     */
    private function createToken(User $user): string
    {
        return $this->tokenGenerator->createToken($user->getId(), $user->getEmail());
    }

    private function getExpiryTime(): int
    {
        $generatedAt = time();

        return $generatedAt + $this->lifetime;
    }
}
