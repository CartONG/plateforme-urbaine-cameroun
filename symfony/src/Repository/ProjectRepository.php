<?php

namespace App\Repository;

use App\Entity\HighlightedItem;
use App\Entity\Project;
use App\Enum\ItemType;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Project>
 */
class ProjectRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Project::class);
    }

    public function findHighlightedItems(array $ids): array
    {
        $results = $this->createQueryBuilder('p')
            ->select('p')
            ->where('p.id IN (:ids)')
            ->setParameter('ids', $ids)
            ->getQuery()
            ->getResult();

        return array_map(function ($item) {
            if ($item instanceof Project) {
                return $this->projectToHighlightedItem($item);
            }

            return null;
        }, $results);
    }

    private function projectToHighlightedItem(Project $item): HighlightedItem
    {
        $highlightedItem = new HighlightedItem();

        return $highlightedItem
            ->setItemId($item->getId())
            ->setItemType(ItemType::PROJECT)
            ->setImage($item->getLogo())
            ->setName($item->getName())
            ->setSlug($item->getSlug())
            ->setDescription($item->getDescription())
            ->setUpdatedAt($item->getUpdatedAt());
    }

    public function findTwoSimilarProjectsByThematics(Project $project): array
    {
        return $this->createQueryBuilder('p')
            ->leftJoin('p.thematics', 't')
            ->addSelect('t')
            ->andWhere('p.id != :id')
            ->andWhere('t.id IN (:thematicIds)')
            ->setParameter('thematicIds', $project->getThematics()->map(fn ($thematic) => $thematic->getId()))
            ->setParameter('id', $project->getId())
            ->orderBy('p.updatedAt', 'DESC')
            ->setMaxResults(2)
            ->getQuery()
            ->getResult()
        ;
    }
    //    /**
    //     * @return Project[] Returns an array of Project objects
    //     */
    //    public function findByExampleField($value): array
    //    {
    //        return $this->createQueryBuilder('p')
    //            ->andWhere('p.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->orderBy('p.id', 'ASC')
    //            ->setMaxResults(10)
    //            ->getQuery()
    //            ->getResult()
    //        ;
    //    }

    //    public function findOneBySomeField($value): ?Project
    //    {
    //        return $this->createQueryBuilder('p')
    //            ->andWhere('p.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}
