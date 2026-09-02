<?php

namespace App\Services\Service\DiverCity;

use App\Entity\DiverCity\Space;
use App\Repository\DiverCity\BlockedPeriodRepository;
use App\Repository\DiverCity\BookingRepository;
use App\Repository\DiverCity\SpaceAdminRepository;

class KpiCalculator
{
    public function __construct(
        private BookingRepository $bookingRepository,
        private SpaceAdminRepository $spaceAdminRepository,
        private BlockedPeriodRepository $blockedPeriodRepository,
    ) {
    }

    public function computeForSpace(Space $space, \DateTimeInterface $from, \DateTimeInterface $to): array
    {
        return [
            'bookingsCount' => $this->bookingRepository->countForSpaceBetween($space, $from, $to),
            'acceptedBookingsCount' => $this->bookingRepository->countForSpaceBetween($space, $from, $to, 'ACCEPTEE'),
            'pendingBookingsCount' => $this->bookingRepository->countPendingForSpace($space),
            'cancelledRate' => $this->bookingRepository->cancelledRateForSpace($space, $from, $to),
            'occupancyRate' => $this->bookingRepository->occupancyRateForSpace($space, $from, $to, $this->blockedPeriodRepository),
            'activeUsersCount' => $this->bookingRepository->distinctUsersCountForSpace($space, $from, $to),
            'repeatUsersRate' => $this->bookingRepository->repeatUsersRateForSpace($space, $from, $to),
            'averageLeadTimeHours' => $this->bookingRepository->averageLeadTimeHoursForSpace($space, $from, $to),
            'spaceAdminsCount' => $this->spaceAdminRepository->count(['space' => $space]),
        ];
    }
}