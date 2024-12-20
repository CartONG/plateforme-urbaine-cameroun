<?php

namespace App\Services\Service\EmailVerifier;

use App\Repository\User\UserRepository;
use App\Services\Service\EmailVerifier\Dto\EmailVerifierVerifyDto;
use App\Services\Service\EmailVerifier\Exception\SignatureParamsException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class EmailVerifierService
{
    public function __construct(
        private readonly EmailVerifierSignature $signature,
        private readonly UserRepository $userRepository,
    ) {
    }

    /**
     * @throws \JsonException
     */
    public function getSignature(): EmailVerifierSignature
    {
        return $this->signature;
    }

    /**
     * @throws SignatureParamsException
     */
    public function validSignatureAndUser(EmailVerifierVerifyDto $emailVerifierDto): void
    {
        $this->signature->acceptEmailVerifierDto($emailVerifierDto);

        $this->signature->acceptSignatureHash($emailVerifierDto->email, $emailVerifierDto->expiresAt, $emailVerifierDto->token);

        $user = $this->userRepository->findOneBy(['email' => $emailVerifierDto->email]);

        if (!$user) {
            throw new NotFoundHttpException();
        }

        $this->signature->verifySignatureHash($user, $emailVerifierDto->expiresAt, $emailVerifierDto->token);

        $user->setIsValidated(true);

        $this->userRepository->save($user);
    }
}
