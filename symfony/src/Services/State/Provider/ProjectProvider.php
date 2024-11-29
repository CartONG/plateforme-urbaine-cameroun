<?php

namespace App\Services\State\Provider;

use ApiPlatform\Metadata\Operation;
use ApiPlatform\State\ProviderInterface;
use App\Model\Enums\UserRoles;
use App\Repository\ProjectRepository;
use Symfony\Bundle\SecurityBundle\Security;

final class ProjectProvider implements ProviderInterface
{
    public function __construct(
        private ProjectRepository $projectRepository,
        private Security $security,
    ) {
    }

    public function provide(Operation $operation, array $uriVariables = [], array $context = []): array
    {
        if ($this->security->isGranted(UserRoles::ROLE_ADMIN)) {
            return $this->projectRepository->findAll();
        }

        return $this->projectRepository->findBy(['isValidated' => true]);
    }
}
