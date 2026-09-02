<?php

namespace App\Repository\DiverCity;

use ApiPlatform\Doctrine\Orm\Paginator as ApiPlatformPaginator;
use App\Entity\DiverCity\Booking;
use App\Entity\DiverCity\Space;
use App\Entity\User\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\Tools\Pagination\Paginator as DoctrinePaginator;
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

    public function getCurrentStatusCode(Uuid $bookingId): ?string
    {
        $result = $this->createQueryBuilder('b')
            ->select('s.code')
            ->join('b.status', 's')
            ->where('b.id = :id')
            ->setParameter('id', $bookingId)
            ->getQuery()
            ->getOneOrNullResult();

        return $result['code'] ?? null;
    }

    /**
     * Renvoie les réservations d'un utilisateur, tous statuts confondus,
     * triées par date décroissante, avec pagination Doctrine/API Platform.
     */
    public function findByUserOrderedByDate(User $user, int $page = 1, int $itemsPerPage = 20): ApiPlatformPaginator
    {
        $query = $this->createQueryBuilder('b')
            ->andWhere('b.user = :user')
            ->setParameter('user', $user)
            ->orderBy('b.date', 'DESC')
            ->addOrderBy('b.startTime', 'DESC')
            ->setFirstResult(($page - 1) * $itemsPerPage)
            ->setMaxResults($itemsPerPage)
            ->getQuery();

        return new ApiPlatformPaginator(new DoctrinePaginator($query));
    }

    /**
     * Nombre total de réservations sur un espace, dans une plage de dates,
     * éventuellement filtré par statut.
     */
    public function countForSpaceBetween(Space $space, \DateTimeInterface $from, \DateTimeInterface $to, ?string $statusCode = null): int
    {
        $qb = $this->createQueryBuilder('b')
            ->select('COUNT(b.id)')
            ->andWhere('b.space = :space')
            ->andWhere('b.date >= :from')
            ->andWhere('b.date <= :to')
            ->setParameter('space', $space)
            ->setParameter('from', $from)
            ->setParameter('to', $to);

        if (null !== $statusCode) {
            $qb->innerJoin('b.status', 's')
                ->andWhere('s.code = :code')
                ->setParameter('code', $statusCode);
        }

        return (int) $qb->getQuery()->getSingleScalarResult();
    }

    /**
     * Taux d'annulation sur la période (réservations ANNULEE / total).
     */
    public function cancelledRateForSpace(Space $space, \DateTimeInterface $from, \DateTimeInterface $to): float
    {
        $total = $this->countForSpaceBetween($space, $from, $to);
        if (0 === $total) {
            return 0.0;
        }
        $cancelled = $this->countForSpaceBetween($space, $from, $to, 'ANNULEE');

        return round($cancelled / $total, 4);
    }

    /**
     * Nombre d'utilisateurs distincts ayant réservé sur la période.
     */
    public function distinctUsersCountForSpace(Space $space, \DateTimeInterface $from, \DateTimeInterface $to): int
    {
        return (int) $this->createQueryBuilder('b')
            ->select('COUNT(DISTINCT b.user)')
            ->andWhere('b.space = :space')
            ->andWhere('b.date >= :from')
            ->andWhere('b.date <= :to')
            ->setParameter('space', $space)
            ->setParameter('from', $from)
            ->setParameter('to', $to)
            ->getQuery()
            ->getSingleScalarResult();
    }

    /**
     * Réservations en attente (toutes dates confondues) — file d'attente actuelle.
     */
    public function countPendingForSpace(Space $space): int
    {
        return $this->countForSpaceBetween($space, new \DateTimeImmutable('1970-01-01'), new \DateTimeImmutable('2100-01-01'), 'EN_ATTENTE');
    }

    /**
     * Délai moyen (en heures) entre la création de la demande et la date de créneau réservé,
     * sur les réservations acceptées.
     */
    public function averageLeadTimeHoursForSpace(Space $space, \DateTimeInterface $from, \DateTimeInterface $to): ?float
    {
        $bookings = $this->createQueryBuilder('b')
            ->innerJoin('b.status', 's')
            ->andWhere('b.space = :space')
            ->andWhere('b.date >= :from')
            ->andWhere('b.date <= :to')
            ->andWhere('s.code = :code')
            ->setParameter('space', $space)
            ->setParameter('from', $from)
            ->setParameter('to', $to)
            ->setParameter('code', 'ACCEPTEE')
            ->getQuery()
            ->getResult();

        if (empty($bookings)) {
            return null;
        }

        $totalHours = 0;
        foreach ($bookings as $booking) {
            $slotStart = \DateTime::createFromInterface($booking->getDate())
                ->setTime((int) $booking->getStartTime()->format('H'), (int) $booking->getStartTime()->format('i'));
            $diff = $slotStart->getTimestamp() - $booking->getSubmittedAt()->getTimestamp();
            $totalHours += $diff / 3600;
        }

        return round($totalHours / count($bookings), 1);
    }

    /**
     * Taux de réservations récurrentes : part des utilisateurs ayant réservé
     * plus d'une fois sur la période.
     */
    public function repeatUsersRateForSpace(Space $space, \DateTimeInterface $from, \DateTimeInterface $to): float
    {
        $rows = $this->createQueryBuilder('b')
            ->select('IDENTITY(b.user) as userId', 'COUNT(b.id) as bookingCount')
            ->andWhere('b.space = :space')
            ->andWhere('b.date >= :from')
            ->andWhere('b.date <= :to')
            ->setParameter('space', $space)
            ->setParameter('from', $from)
            ->setParameter('to', $to)
            ->groupBy('b.user')
            ->getQuery()
            ->getResult();

        if (empty($rows)) {
            return 0.0;
        }

        $repeatCount = count(array_filter($rows, fn ($r) => $r['bookingCount'] > 1));

        return round($repeatCount / count($rows), 4);
    }

    private const OPENING_HOUR = 8.5;  // 8h30
    private const CLOSING_HOUR = 17.5; // 17h30
    private const DAILY_HOURS = self::CLOSING_HOUR - self::OPENING_HOUR; // 9h

    public function occupancyRateForSpace(Space $space, \DateTimeInterface $from, \DateTimeInterface $to, BlockedPeriodRepository $blockedPeriodRepository): float
    {
        $bookings = $this->createQueryBuilder('b')
            ->innerJoin('b.status', 's')
            ->andWhere('b.space = :space')
            ->andWhere('b.date >= :from')
            ->andWhere('b.date <= :to')
            ->andWhere('s.code = :code')
            ->setParameter('space', $space)
            ->setParameter('from', $from)
            ->setParameter('to', $to)
            ->setParameter('code', 'ACCEPTEE')
            ->getQuery()
            ->getResult();

        $bookedHours = 0.0;
        foreach ($bookings as $booking) {
            $start = (int) $booking->getStartTime()->format('H') + ((int) $booking->getStartTime()->format('i') / 60);
            $end = (int) $booking->getEndTime()->format('H') + ((int) $booking->getEndTime()->format('i') / 60);
            $bookedHours += max(0, $end - $start);
        }

        $adjustments = $blockedPeriodRepository->getAvailabilityAdjustmentPerDay($space, $from, $to);

        $availableHours = 0.0;
        $cursor = \DateTime::createFromInterface($from);
        $end = \DateTime::createFromInterface($to);
        while ($cursor <= $end) {
            $dateKey = $cursor->format('Y-m-d');
            $isWeekday = (int) $cursor->format('N') <= 5;
            $adjustment = $adjustments[$dateKey] ?? 0.0;

            $dayHours = $isWeekday
                ? max(0, min(self::DAILY_HOURS, self::DAILY_HOURS - $adjustment))
                : max(0, -$adjustment); // week-end : uniquement les ouvertures exceptionnelles

            $availableHours += $dayHours;
            $cursor->modify('+1 day');
        }

        if (0.0 === $availableHours) {
            return 0.0;
        }

        return round($bookedHours / $availableHours, 4);
    }
}
