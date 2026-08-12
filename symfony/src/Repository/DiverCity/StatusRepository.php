<?php

namespace App\Repository\DiverCity;

use App\Entity\DiverCity\Status;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Status>
 */
class StatusRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Status::class);
    }

    /**
     * Raccourci utilisé par les processors de réservation pour retrouver
     * un statut par son code métier (EN_ATTENTE, ACCEPTEE, REFUSEE, ANNULEE...).
     */
    public function findOneByCode(string $code): ?Status
    {
        return $this->findOneBy(['code' => $code]);
    }
}