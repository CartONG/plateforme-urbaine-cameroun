<?php

namespace App\Repository;

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
        return $this->createQueryBuilder('p')
            ->select("p.id, p.id as itemId, p.name, p.updatedAt, p.description, p.slug, l.filePath as image, '".ItemType::PROJECT->value."' as itemType")
            ->where('p.id IN (:ids)')
            ->setParameter('ids', $ids)
            ->leftJoin('p.logo', 'l')
            ->getQuery()
            ->getResult()
        ;
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
