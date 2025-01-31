<?php

namespace App\Services\Extension\Collection;

use ApiPlatform\Doctrine\Orm\Extension\QueryCollectionExtensionInterface;
use ApiPlatform\Doctrine\Orm\Util\QueryNameGeneratorInterface;
use ApiPlatform\Metadata\Operation;
use App\Entity\Actor;
use App\Entity\Project;
use App\Entity\Resource;
use App\Model\Enums\UserRoles;
use Doctrine\ORM\QueryBuilder;
use Symfony\Bundle\SecurityBundle\Security;

final readonly class isValidatedExtension implements QueryCollectionExtensionInterface
{
    public function __construct(
        private Security $security,
    ) {
    }

    public function applyToCollection(QueryBuilder $queryBuilder, QueryNameGeneratorInterface $queryNameGenerator, string $resourceClass, ?Operation $operation = null, array $context = []): void
    {
        if (!$this->isSupport($resourceClass) || $this->security->isGranted(UserRoles::ROLE_ADMIN)) {
            return;
        }

        $this->addWhere($queryBuilder, $resourceClass);
    }

    private function isSupport(string $resourceClass): bool
    {
        return in_array($resourceClass, [Actor::class, Resource::class, Project::class]);
    }

    private function addWhere(QueryBuilder $queryBuilder, string $resourceClass): void
    {
        $rootAlias = $queryBuilder->getRootAliases()[0];
        $queryBuilder->andWhere(sprintf('%s.isValidated = :isValidated', $rootAlias));
        $queryBuilder->setParameter('isValidated', true);
    }
}
