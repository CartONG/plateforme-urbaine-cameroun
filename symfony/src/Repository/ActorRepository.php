<?php

namespace App\Repository;

use App\Entity\Actor;
use App\Entity\HighlightedItem;
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
        $results = $this->createQueryBuilder('a')
            ->select('a')
            ->where('a.id IN (:ids)')
            ->setParameter('ids', $ids)
            ->getQuery()
            ->getResult();

        return array_map(function ($item) {
            if ($item instanceof Actor) {
                return $this->actorToHighlightedItem($item);
            }

            return null;
        }, $results);
    }

    private function actorToHighlightedItem(Actor $item): HighlightedItem
    {
        $highlightedItem = new HighlightedItem();

        return $highlightedItem
            ->setItemId($item->getId())
            ->setItemType(ItemType::ACTOR)
            ->setImage($item->getLogo())
            ->setName($item->getName())
            ->setSlug($item->getSlug())
            ->setDescription($item->getDescription())
            ->setUpdatedAt($item->getUpdatedAt());
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
