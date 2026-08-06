<?php

namespace App\Repository\DiverCity;

use App\Entity\DiverCity\Booking;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

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
        \Ramsey\Uuid\UuidInterface|string $spaceId,
        \DateTimeInterface $date,
        \DateTimeInterface $startTime,
        \DateTimeInterface $endTime,
        ?string $excludeBookingId = null,
    ): bool {
        $qb = $this->createQueryBuilder('b')
            ->innerJoin('b.status', 's')
            ->andWhere('b.space = :spaceId')
            ->andWhere('b.date = :date')
            ->andWhere('s.code = :acceptedCode')
            ->andWhere('b.startTime < :endTime')
            ->andWhere('b.endTime > :startTime')
            ->setParameter('spaceId', $spaceId)
            ->setParameter('date', $date)
            ->setParameter('acceptedCode', 'ACCEPTEE')
            ->setParameter('startTime', $startTime)
            ->setParameter('endTime', $endTime);

        if (null !== $excludeBookingId) {
            $qb->andWhere('b.id != :excludeId')->setParameter('excludeId', $excludeBookingId);
        }

        return null !== $qb->getQuery()->setMaxResults(1)->getOneOrNullResult();
    }
}
