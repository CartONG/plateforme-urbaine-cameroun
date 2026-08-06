<?php

namespace App\Repository\DiverCity;

use App\Entity\DiverCity\BlockedPeriod;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<BlockedPeriod>
 */
class BlockedPeriodRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, BlockedPeriod::class);
    }
}
