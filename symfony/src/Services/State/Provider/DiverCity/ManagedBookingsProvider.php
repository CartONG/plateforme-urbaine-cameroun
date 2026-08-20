<?php

namespace App\Services\State\Provider\DiverCity;

use ApiPlatform\Metadata\Operation;
use ApiPlatform\State\ProviderInterface;
use App\Repository\DiverCity\BookingRepository;
use App\Repository\DiverCity\SpaceAdminRepository;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;

/**
 * Fournit la liste des réservations des espaces administrés par
 * l'utilisateur connecté (GET /divercity/bookings/managed).
 *
 * Si l'utilisateur a ROLE_ADMIN (PDC), renvoie toutes les réservations.
 * Si l'utilisateur n'administre aucun espace, l'accès est refusé (403) :
 * cette route est réservée aux profils ayant un rôle de gestion.
 */
class ManagedBookingsProvider implements ProviderInterface
{
    public function __construct(
        private Security $security,
        private BookingRepository $bookingRepository,
        private SpaceAdminRepository $spaceAdminRepository,
    ) {
    }

    public function provide(Operation $operation, array $uriVariables = [], array $context = []): iterable
    {
        $currentUser = $this->security->getUser();
        if (!$currentUser instanceof \App\Entity\User\User) {
            throw new AccessDeniedException('Vous devez être connecté pour consulter les réservations gérées.');
        }

        if (in_array('ROLE_ADMIN', $currentUser->getRoles(), true)) {
            return $this->bookingRepository->findBy([], ['submittedAt' => 'DESC']);
        }

        $managedSpaces = $this->spaceAdminRepository->findSpacesAdministeredBy($currentUser);
        if ([] === $managedSpaces) {
            throw new AccessDeniedException('Vous n\'administrez aucun espace.');
        }

        return $this->bookingRepository->findBy(['space' => $managedSpaces], ['submittedAt' => 'DESC']);
    }
}
