<?php

namespace App\Repository\DiverCity;

use App\Entity\DiverCity\SpaceStatistic;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<SpaceStatistic>
 */
class SpaceStatisticRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, SpaceStatistic::class);
    }
}