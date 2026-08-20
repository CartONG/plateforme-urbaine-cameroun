<?php

namespace App\Services\State\Provider\DiverCity;

use ApiPlatform\Metadata\Operation;
use ApiPlatform\State\ProviderInterface;
use App\Repository\DiverCity\BookingRepository;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpKernel\Exception\UnprocessableEntityHttpException;

/**
 * Fournit la liste des réservations de l'utilisateur actuellement connecté
 * (GET /divercity/bookings/mine).
 */
class MyBookingsProvider implements ProviderInterface
{
    public function __construct(
        private Security $security,
        private BookingRepository $bookingRepository,
    ) {
    }

    public function provide(Operation $operation, array $uriVariables = [], array $context = []): iterable
    {
        $currentUser = $this->security->getUser();
        if (!$currentUser instanceof \App\Entity\User\User) {
            throw new UnprocessableEntityHttpException('Vous devez être connecté pour consulter vos réservations.');
        }

        return $this->bookingRepository->findBy(['user' => $currentUser], ['submittedAt' => 'DESC']);
    }
}