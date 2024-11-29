<?php

namespace App\Services\State\Provider;

use ApiPlatform\Metadata\Operation;
use ApiPlatform\State\ProviderInterface;
use App\Model\Enums\UserRoles;
use App\Repository\UserRepository;
use Symfony\Bundle\SecurityBundle\Security;

final class UserProvider implements ProviderInterface
{
    public function __construct(
        private UserRepository $userRepository,
        private Security $security,
    ) {
    }

    public function provide(Operation $operation, array $uriVariables = [], array $context = []): array
    {
        if ($this->security->isGranted(UserRoles::ROLE_ADMIN)) {
            return $this->userRepository->findAll();
        }

        return $this->userRepository->findBy(['isValidated' => true]);
    }
}
