<?php

namespace App\Services\State\Provider\DiverCity;

use ApiPlatform\Metadata\Operation;
use ApiPlatform\State\Pagination\PaginatorInterface;
use ApiPlatform\State\ProviderInterface;
use App\Repository\DiverCity\BookingRepository;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpKernel\Exception\UnprocessableEntityHttpException;

/**
 * Fournit les réservations paginées du demandeur actuellement connecté
 * (GET /divercity/bookings/mine?page=...), toutes statuts confondus.
 */
class MyBookingsProvider implements ProviderInterface
{
    private const ITEMS_PER_PAGE = 20;

    public function __construct(
        private Security $security,
        private BookingRepository $bookingRepository,
        private RequestStack $requestStack,
    ) {
    }

    public function provide(Operation $operation, array $uriVariables = [], array $context = []): PaginatorInterface
    {
        $user = $this->security->getUser();
        if (!$user instanceof \App\Entity\User\User) {
            throw new UnprocessableEntityHttpException('Vous devez être connecté pour consulter vos réservations.');
        }

        $request = $this->requestStack->getCurrentRequest();
        $page = max(1, (int) ($request?->query->get('page') ?? 1));

        return $this->bookingRepository->findByUserOrderedByDate($user, $page, self::ITEMS_PER_PAGE);
    }
}