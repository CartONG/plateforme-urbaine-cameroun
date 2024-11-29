<?php

namespace App\Services\State\Provider;

use ApiPlatform\Metadata\Operation;
use ApiPlatform\State\ProviderInterface;
use App\Model\Enums\UserRoles;
use App\Repository\ActorRepository;
use Symfony\Bundle\SecurityBundle\Security;

final class ActorProvider implements ProviderInterface
{
    public function __construct(
        private ActorRepository $actorRepository,
        private Security $security,
    ) {
    }

    public function provide(Operation $operation, array $uriVariables = [], array $context = []): array
    {
        if ($this->security->isGranted(UserRoles::ROLE_ADMIN)) {
            return $this->actorRepository->findAll();
        }

        return $this->actorRepository->findBy(['isValidated' => true]);
    }
}
