<?php

namespace App\Services\State\Provider;
use App\Model\Enums\UserRoles;
use ApiPlatform\Metadata\Operation;
use App\Repository\ProjectRepository;
use ApiPlatform\State\ProviderInterface;
use Symfony\Bundle\SecurityBundle\Security;

final class ProjectProvider implements ProviderInterface
{
    
    public function __construct(
        private ProjectRepository $projectRepository,
        private Security $security
    )
    {
    }

    public function provide(Operation $operation, array $uriVariables = [], array $context = []): array
    {
        if ($this->security->isGranted(UserRoles::ROLE_ADMIN)) {
            return $this->projectRepository->findAll();
        }

        return $this->projectRepository->findBy(['isValidated' => true]);
    }
}