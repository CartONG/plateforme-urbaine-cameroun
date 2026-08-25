<?php

namespace App\Repository\DiverCity;

use App\Entity\DiverCity\Booking;
use App\Entity\DiverCity\Space;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\Uid\Uuid;

/**
 * @extends ServiceEntityRepository<Booking>
 */
class BookingRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Booking::class);
    }

    /**
     * Vérifie si un créneau est déjà occupé par une réservation validée sur
     * cet espace (utilisé avant validation d'une nouvelle demande).
     */
    public function hasConflictingBooking(
        Space $space,
        \DateTimeInterface $date,
        \DateTimeInterface $startTime,
        \DateTimeInterface $endTime,
        ?Uuid $excludeBookingId = null,
    ): bool {
        $qb = $this->createQueryBuilder('b')
            ->innerJoin('b.status', 's')
            ->andWhere('b.space = :space')
            ->andWhere('b.date = :date')
            ->andWhere('s.code = :acceptedCode')
            ->andWhere('b.startTime < :endTime')
            ->andWhere('b.endTime > :startTime')
            ->setParameter('space', $space)
            ->setParameter('date', $date)
            ->setParameter('acceptedCode', 'ACCEPTEE')
            ->setParameter('startTime', $startTime)
            ->setParameter('endTime', $endTime);

        if (null !== $excludeBookingId) {
            $qb->andWhere('b.id != :excludeId')->setParameter('excludeId', $excludeBookingId);
        }

        return null !== $qb->getQuery()->setMaxResults(1)->getOneOrNullResult();
    }

    /**
     * Renvoie toutes les réservations acceptées sur un espace, dans une
     * plage de dates donnée (utilisé pour l'endpoint de disponibilités).
     *
     * @return Booking[]
     */
    public function findAcceptedBetween(Space $space, \DateTimeInterface $dateFrom, \DateTimeInterface $dateTo): array
    {
        return $this->createQueryBuilder('b')
            ->innerJoin('b.status', 's')
            ->andWhere('b.space = :space')
            ->andWhere('b.date >= :dateFrom')
            ->andWhere('b.date <= :dateTo')
            ->andWhere('s.code = :acceptedCode')
            ->setParameter('space', $space)
            ->setParameter('dateFrom', $dateFrom)
            ->setParameter('dateTo', $dateTo)
            ->setParameter('acceptedCode', 'ACCEPTEE')
            ->orderBy('b.date', 'ASC')
            ->addOrderBy('b.startTime', 'ASC')
            ->getQuery()
            ->getResult();
    }

    /**
     * Renvoie les réservations acceptées à venir (date >= aujourd'hui),
     * triées par date croissante, pour affichage public.
     *
     * @return Booking[]
     */
    public function findAcceptedUpcoming(int $limit = 20): array
    {
        return $this->createQueryBuilder('b')
            ->innerJoin('b.status', 's')
            ->andWhere('s.code = :acceptedCode')
            ->andWhere('b.date >= :today')
            ->setParameter('acceptedCode', 'ACCEPTEE')
            ->setParameter('today', new \DateTime('today'))
            ->orderBy('b.date', 'ASC')
            ->addOrderBy('b.startTime', 'ASC')
            ->setMaxResults($limit)
            ->getQuery()
            ->getResult();
    }
}
