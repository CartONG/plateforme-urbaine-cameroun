<?php

namespace App\Services\State\Provider\User;

use ApiPlatform\Metadata\Operation;
use ApiPlatform\State\ProviderInterface;
use App\Entity\User\User;
use App\Services\Mailer\User\UserEmailVerifierMailer;
use App\Services\Service\EmailVerifier\Exception\SignatureParamsException;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\Mailer\Exception\TransportExceptionInterface;

final class UserVerifyEmailProvider implements ProviderInterface
{
    public function __construct(
        private readonly Security $security,
        private readonly UserEmailVerifierMailer $userEmailVerifierMailer,
    ) {
    }

    /**
     * @throws SignatureParamsException
     * @throws TransportExceptionInterface
     * @throws \JsonException
     */
    public function provide(Operation $operation, array $uriVariables = [], array $context = []): array|object|null
    {
        $user = $this->security->getUser();

        /** @var User $user */
        if (!$user instanceof User || $user->getIsValidated()) {
            return null;
        }

        $this->userEmailVerifierMailer->send($user);

        return [];
    }
}
