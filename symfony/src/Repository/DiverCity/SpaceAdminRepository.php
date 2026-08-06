<?php

namespace App\Repository\DiverCity;

use App\Entity\DiverCity\SpaceAdmin;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<SpaceAdmin>
 */
class SpaceAdminRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, SpaceAdmin::class);
    }
}
