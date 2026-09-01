<?php

namespace App\Repository\DiverCity;

use App\Entity\DiverCity\HighlightedResource;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<HighlightedResource>
 */
class HighlightedResourceRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, HighlightedResource::class);
    }

    public function findOneByResourceId(string $resourceId): ?HighlightedResource
    {
        return $this->findOneBy(['resourceId' => $resourceId]);
    }

    /**
     * @return HighlightedResource[]
     */
    public function findAllOrderedByPosition(): array
    {
        return $this->createQueryBuilder('h')
            ->orderBy('h.position', 'ASC')
            ->getQuery()
            ->getResult();
    }

    /**
     * Renvoie les ressources mises en avant, actives, triées par position,
     * limitées à $limit (3 par défaut) — pour l'endpoint public /main.
     *
     * @return HighlightedResource[]
     */
    public function findMainHighlighted(int $limit = 3): array
    {
        return $this->createQueryBuilder('h')
            ->andWhere('h.isHighlighted = true')
            ->orderBy('h.position', 'ASC')
            ->setMaxResults($limit)
            ->getQuery()
            ->getResult();
    }
}
