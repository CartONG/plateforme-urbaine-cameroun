<?php

namespace App\Repository\DiverCity;

use App\Entity\DiverCity\EventActivityFavorite;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<EventActivityFavorite>
 */
class EventActivityFavoriteRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, EventActivityFavorite::class);
    }
}