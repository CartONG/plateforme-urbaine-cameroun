<?php

namespace App\Repository;

use App\Entity\Resource;
use App\Enum\ItemType;
use App\Enum\ResourceType;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Resource>
 */
class ResourceRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Resource::class);
    }

    public function findHighlightedItems(array $ids): array
    {
        return $this->createQueryBuilder('r')
            ->select("r.id, r.id as itemId, r.name, r.updatedAt, r.description, COALESCE(r.link, f.filePath) as link, '".ItemType::RESOURCE->value."' as itemType")
            ->where('r.id IN (:ids)')
            ->leftJoin('r.file', 'f')
            ->setParameter('ids', $ids)
            ->getQuery()
            ->getResult()
        ;
    }

    public function findNearestEvents(): array
    {
        return $this->createQueryBuilder('r')
            ->select('r')
            ->where("r.type = '".ResourceType::EVENTS->value."'")
            ->andWhere('r.startAt >= :today')
            ->setParameter('today', new \DateTime())
            ->orderBy('r.startAt', 'ASC')
            ->setMaxResults(3)
            ->getQuery()
            ->getResult()
        ;
    }

    //    /**
    //     * @return Resource[] Returns an array of Resource objects
    //     */
    //    public function findByExampleField($value): array
    //    {
    //        return $this->createQueryBuilder('r')
    //            ->andWhere('r.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->orderBy('r.id', 'ASC')
    //            ->setMaxResults(10)
    //            ->getQuery()
    //            ->getResult()
    //        ;
    //    }

    //    public function findOneBySomeField($value): ?Resource
    //    {
    //        return $this->createQueryBuilder('r')
    //            ->andWhere('r.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}
