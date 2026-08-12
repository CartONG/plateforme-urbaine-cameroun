<?php

namespace App\Services\State\Processor\DiverCity;

use ApiPlatform\Metadata\Operation;
use ApiPlatform\State\ProcessorInterface;
use App\Entity\DiverCity\Booking;
use App\Entity\DiverCity\Notification;
use App\Repository\DiverCity\StatusRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\DependencyInjection\Attribute\Autowire;
use Symfony\Component\HttpKernel\Exception\UnprocessableEntityHttpException;

/**
 * Traite l'annulation d'une réservation, par le demandeur ou un administrateur
 * (PATCH /divercity/bookings/{id}/cancel).
 *
 * Applique les règles de gestion de l'étude fonctionnelle §5.2 :
 *   - le demandeur peut annuler sa réservation à tout moment
 *   - l'administrateur peut également annuler une réservation
 *   - toute annulation libère immédiatement le créneau (automatique, puisque
 *     BookingRepository::hasConflictingBooking ne regarde que les
 *     réservations au statut ACCEPTEE)
 *   - un motif d'annulation peut être renseigné (facultatif, contrairement
 *     au refus qui l'exige)
 *   - une notification d'annulation est envoyée au demandeur
 */
class BookingCancellationProcessor implements ProcessorInterface
{
    public function __construct(
        #[Autowire(service: 'api_platform.doctrine.orm.state.persist_processor')]
        private ProcessorInterface $persistProcessor,
        private Security $security,
        private StatusRepository $statusRepository,
        private EntityManagerInterface $entityManager,
    ) {
    }

    /**
     * @param Booking $data
     */
    public function process(mixed $data, Operation $operation, array $uriVariables = [], array $context = []): mixed
    {
        $currentUser = $this->security->getUser();
        if (!$currentUser instanceof \App\Entity\User\User) {
            throw new UnprocessableEntityHttpException('Vous devez être connecté pour annuler une réservation.');
        }

        // On ne peut pas annuler une réservation déjà refusée ou déjà annulée.
        $currentStatusCode = $data->getStatus()?->getCode();
        if (in_array($currentStatusCode, ['REFUSEE', 'ANNULEE'], true)) {
            throw new UnprocessableEntityHttpException('Cette réservation ne peut plus être annulée.');
        }

        $cancelledStatus = $this->statusRepository->findOneBy(['code' => 'ANNULEE']);
        if (null === $cancelledStatus) {
            throw new \RuntimeException('Le statut "ANNULEE" est introuvable en base. Vérifiez le seed de la table divercity.status.');
        }

        $data->setStatus($cancelledStatus);
        $data->setProcessedAt(new \DateTime());

        // Si c'est un admin qui annule (pas le demandeur lui-même), on le trace.
        if ($currentUser !== $data->getUser()) {
            $data->setProcessingUser($currentUser);
        }

        /** @var Booking $booking */
        $booking = $this->persistProcessor->process($data, $operation, $uriVariables, $context);

        // Notification d'annulation (règle de gestion §5.2).
        $notification = new Notification();
        $notification->setBooking($booking);
        $notification->setUser($booking->getUser());
        $notification->setType(Notification::TYPE_CANCELLATION);
        $notification->setContent(sprintf(
            'Votre réservation pour "%s" le %s a été annulée.%s',
            $booking->getSpace()->getName(),
            $booking->getDate()->format('d/m/Y'),
            $booking->getCancellationReason() ? ' Motif : '.$booking->getCancellationReason() : '',
        ));
        $this->entityManager->persist($notification);
        $this->entityManager->flush();

        return $booking;
    }
}