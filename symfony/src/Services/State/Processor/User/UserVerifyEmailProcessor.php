<?php

namespace App\Services\State\Processor\User;

use ApiPlatform\Metadata\Operation;
use ApiPlatform\State\ProcessorInterface;
use App\Entity\User\User;
use App\Repository\User\UserRepository;
use App\Services\Mailer\User\UserEmailVerifierMailer;
use App\Services\Service\EmailVerifier\Dto\EmailVerifierSendDto;
use App\Services\Service\EmailVerifier\Dto\EmailVerifierVerifyDto;
use App\Services\Service\EmailVerifier\EmailVerifierService;
use App\Services\Service\EmailVerifier\Exception\SignatureParamsException;
use Symfony\Component\Mailer\Exception\TransportExceptionInterface;

class UserVerifyEmailProcessor implements ProcessorInterface
{
    public function __construct(
        private readonly UserEmailVerifierMailer $userEmailVerifierMailer,
        private readonly EmailVerifierService $emailVerifierService,
        private readonly UserRepository $userRepository,
    ) {
    }

    /**
     * @param EmailVerifierVerifyDto $data
     *
     * @throws SignatureParamsException
     * @throws TransportExceptionInterface
     * @throws \JsonException
     */
    public function process(mixed $data, Operation $operation, array $uriVariables = [], array $context = []): void
    {
        if ($data instanceof EmailVerifierVerifyDto) {
            $this->emailVerifierService->validSignatureAndUser($data);

            return;
        }

        if ($data instanceof EmailVerifierSendDto) {
            $this->send($data);
        }
    }

    /**
     * @throws SignatureParamsException
     * @throws TransportExceptionInterface
     * @throws \JsonException
     */
    private function send(EmailVerifierSendDto $data): void
    {
        $user = $this->userRepository->findOneBy(['email' => $data->email]);
        if (!$user instanceof User || $user->getIsValidated()) {
            return;
        }

        $this->userEmailVerifierMailer->send($user);
    }
}
