<?php

namespace App\Services\State\Provider;
use App\Model\Enums\UserRoles;
use ApiPlatform\Metadata\Operation;
use App\Repository\ActorRepository;
use ApiPlatform\State\ProviderInterface;
use Symfony\Bundle\SecurityBundle\Security;

final class ActorProvider implements ProviderInterface
{
    
    public function __construct(
        private ActorRepository $actorRepository,
        private Security $security
    ) {}

    public function provide(Operation $operation, array $uriVariables = [], array $context = []): array
    {
        if ($this->security->isGranted(UserRoles::ROLE_ADMIN)) {
            return $this->actorRepository->findAll();
        }

        return $this->actorRepository->findBy(['isValidated' => true]);
    }
}