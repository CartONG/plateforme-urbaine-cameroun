<?php

namespace App\Repository;

use App\Entity\Actor;
use App\Enum\ItemType;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Actor>
 */
class ActorRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Actor::class);
    }

    public function findHighlightedItems(array $ids): array
    {
        return $this->createQueryBuilder('a')
            ->select("a.id, a.id as itemId, a.name, a.updatedAt, a.description, a.slug, l.filePath as image, '".ItemType::ACTOR->value."' as itemType")
            ->where('a.id IN (:ids)')
            ->leftJoin('a.logo', 'l')
            ->setParameter('ids', $ids)
            ->getQuery()
            ->getResult()
        ;
    }
    //    /**
    //     * @return Actor[] Returns an array of Actor objects
    //     */
    //    public function findByExampleField($value): array
    //    {
    //        return $this->createQueryBuilder('a')
    //            ->andWhere('a.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->orderBy('a.id', 'ASC')
    //            ->setMaxResults(10)
    //            ->getQuery()
    //            ->getResult()
    //        ;
    //    }

    //    public function findOneBySomeField($value): ?Actor
    //    {
    //        return $this->createQueryBuilder('a')
    //            ->andWhere('a.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}
