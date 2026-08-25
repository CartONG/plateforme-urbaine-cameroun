<?php

namespace App\Services\State\Provider\DiverCity;

use ApiPlatform\Metadata\Operation;
use ApiPlatform\State\ProviderInterface;
use App\Repository\DiverCity\BookingRepository;

/**
 * Fournit la liste publique des réservations ACCEPTEE pour un espace,
 * sans données personnelles du demandeur (GET /divercity/bookings/public).
 */
class PublicBookingsProvider implements ProviderInterface
{
    public function __construct(
        private BookingRepository $bookingRepository,
    ) {
    }

    public function provide(Operation $operation, array $uriVariables = [], array $context = []): iterable
    {
        return $this->bookingRepository->findAcceptedUpcoming();
    }
}