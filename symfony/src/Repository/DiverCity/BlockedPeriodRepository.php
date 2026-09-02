<?php

namespace App\Repository\DiverCity;

use App\Entity\DiverCity\BlockedPeriod;
use App\Entity\DiverCity\Space;
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

    /**
     * Vérifie si le créneau demandé chevauche une période bloquée
     * (non débloquée) sur cet espace.
     */
    public function isPeriodBlocked(
        Space $space,
        \DateTimeInterface $date,
        \DateTimeInterface $startTime,
        \DateTimeInterface $endTime,
    ): bool {
        $qb = $this->createQueryBuilder('bp')
            ->andWhere('bp.space = :space')
            ->andWhere('bp.date = :date')
            ->andWhere('bp.isUnblocked = false')
            ->andWhere('bp.startTime < :endTime')
            ->andWhere('bp.endTime > :startTime')
            ->setParameter('space', $space)
            ->setParameter('date', $date)
            ->setParameter('startTime', $startTime)
            ->setParameter('endTime', $endTime);

        return null !== $qb->getQuery()->setMaxResults(1)->getOneOrNullResult();
    }

    /**
     * Renvoie toutes les périodes bloquées (non débloquées) sur un espace,
     * dans une plage de dates donnée (utilisé pour l'endpoint de disponibilités).
     *
     * @return BlockedPeriod[]
     */
    public function findBlockedBetween(Space $space, \DateTimeInterface $dateFrom, \DateTimeInterface $dateTo): array
    {
        return $this->createQueryBuilder('bp')
            ->andWhere('bp.space = :space')
            ->andWhere('bp.date >= :dateFrom')
            ->andWhere('bp.date <= :dateTo')
            ->andWhere('bp.isUnblocked = false')
            ->setParameter('space', $space)
            ->setParameter('dateFrom', $dateFrom)
            ->setParameter('dateTo', $dateTo)
            ->orderBy('bp.date', 'ASC')
            ->addOrderBy('bp.startTime', 'ASC')
            ->getQuery()
            ->getResult();
    }

    /**
     * Pour chaque date de la période, renvoie la variation nette de disponibilité :
     * positif = heures retirées (bloqué), négatif = heures ajoutées (débloqué exceptionnellement).
     *
     * @return array<string, float> date (Y-m-d) => heures
     */
    public function getAvailabilityAdjustmentPerDay(Space $space, \DateTimeInterface $from, \DateTimeInterface $to): array
    {
        $periods = $this->createQueryBuilder('bp')
            ->andWhere('bp.space = :space')
            ->andWhere('bp.date >= :from')
            ->andWhere('bp.date <= :to')
            ->setParameter('space', $space)
            ->setParameter('from', $from)
            ->setParameter('to', $to)
            ->getQuery()
            ->getResult();

        $adjustments = [];
        foreach ($periods as $period) {
            $start = (int) $period->getStartTime()->format('H') + ((int) $period->getStartTime()->format('i') / 60);
            $end = (int) $period->getEndTime()->format('H') + ((int) $period->getEndTime()->format('i') / 60);
            $hours = max(0, $end - $start);
            $dateKey = $period->getDate()->format('Y-m-d');

            $sign = $period->isUnblocked() ? -1 : 1;
            $adjustments[$dateKey] = ($adjustments[$dateKey] ?? 0) + $sign * $hours;
        }

        return $adjustments;
    }
}
