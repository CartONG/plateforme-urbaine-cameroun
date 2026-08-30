<?php

namespace App\Services\State\Processor\DiverCity;

use ApiPlatform\Metadata\Operation;
use ApiPlatform\State\ProcessorInterface;
use App\Entity\DiverCity\Booking;
use App\Repository\DiverCity\BookingRepository;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\DependencyInjection\Attribute\Autowire;
use Symfony\Component\HttpKernel\Exception\UnprocessableEntityHttpException;

/**
 * Permet au demandeur de modifier le contenu de sa réservation tant
 * qu'elle n'a pas encore été traitée (PATCH /divercity/bookings/{id}/edit).
 *
 * Règle de gestion : une réservation n'est modifiable que si elle est
 * encore EN_ATTENTE. Une fois acceptée, refusée ou annulée, son contenu
 * est figé (pour refuser/annuler, il faut passer par les routes dédiées).
 */
class BookingEditProcessor implements ProcessorInterface
{
    public function __construct(
        #[Autowire(service: 'api_platform.doctrine.orm.state.persist_processor')]
        private ProcessorInterface $persistProcessor,
        private Security $security,
        private BookingRepository $bookingRepository,
    ) {
    }

    /**
     * @param Booking $data
     */
    public function process(mixed $data, Operation $operation, array $uriVariables = [], array $context = []): mixed
    {
        $currentUser = $this->security->getUser();
        if (!$currentUser instanceof \App\Entity\User\User) {
            throw new UnprocessableEntityHttpException('Vous devez être connecté pour modifier une réservation.');
        }

        $currentStatusCode = $this->bookingRepository->getCurrentStatusCode($data->getId());
        if ('EN_ATTENTE' !== $currentStatusCode) {
            throw new UnprocessableEntityHttpException('Seule une réservation en attente peut être modifiée.');
        }

        /* @var Booking $booking */
        return $this->persistProcessor->process($data, $operation, $uriVariables, $context);
    }
}
