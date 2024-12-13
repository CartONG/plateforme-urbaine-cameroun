<?php

namespace App\Services\State\Processor\User;

use ApiPlatform\Metadata\Operation;
use ApiPlatform\State\ProcessorInterface;
use App\Services\Service\EmailVerifier\EmailVerifierDto;
use App\Services\Service\EmailVerifier\EmailVerifierService;
use App\Services\Service\EmailVerifier\Exception\SignatureParamsException;

class UserVerifyEmailProcessor implements ProcessorInterface
{
    public function __construct(
        private readonly EmailVerifierService $emailVerifierService,
    ) {
    }

    /**
     * @param EmailVerifierDto $data
     *
     * @throws SignatureParamsException
     */
    public function process(mixed $data, Operation $operation, array $uriVariables = [], array $context = []): void
    {
        $this->emailVerifierService->validSignatureAndUser($data);
    }
}
