<?php

namespace App\Repository\DiverCity;

use App\Entity\DiverCity\SpaceHighlight;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<SpaceHighlight>
 */
class SpaceHighlightRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, SpaceHighlight::class);
    }
}
