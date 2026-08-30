<?php

namespace App\Services\State\Processor\DiverCity;

use ApiPlatform\Metadata\Operation;
use ApiPlatform\State\ProcessorInterface;
use App\Entity\DiverCity\Booking;
use App\Entity\DiverCity\Notification;
use App\Repository\DiverCity\BookingRepository;
use App\Repository\DiverCity\StatusRepository;
use Doctrine\ORM\EntityManagerInterface;
use Psr\Log\LoggerInterface;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\DependencyInjection\Attribute\Autowire;
use Symfony\Component\HttpKernel\Exception\UnprocessableEntityHttpException;

/**
 * Traite l'annulation d'une réservation, par le demandeur ou un administrateur
 * (PATCH /divercity/bookings/{id}/cancel).
 */
class BookingCancellationProcessor implements ProcessorInterface
{
    public function __construct(
        #[Autowire(service: 'api_platform.doctrine.orm.state.persist_processor')]
        private ProcessorInterface $persistProcessor,
        private Security $security,
        private BookingRepository $bookingRepository,
        private StatusRepository $statusRepository,
        private EntityManagerInterface $entityManager,
        private LoggerInterface $logger,
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

        // On relit l'état RÉEL en base plutôt que de faire confiance à $data->getStatus()
        // (même principe que dans BookingDecisionProcessor) : même si le champ status
        // n'est plus accepté en écriture sur cette route depuis le fix des groupes,
        // ça reste la vérification la plus sûre et la plus explicite.
        $currentStatusCode = $this->bookingRepository->getCurrentStatusCode($data->getId());
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
        if ($currentUser->getId() !== $data->getUser()?->getId()) {
            $data->setProcessingUser($currentUser);
        }

        /** @var Booking $booking */
        $booking = $this->persistProcessor->process($data, $operation, $uriVariables, $context);

        // Notification d'annulation (règle de gestion §5.2), best-effort :
        // un échec ici ne doit jamais transformer une annulation réussie en 500.
        try {
            $notification = new Notification();
            $notification->setBooking($booking);
            $notification->setUser($booking->getUser());
            $notification->setType(Notification::TYPE_CANCELLATION);
            $notification->setSentAt(new \DateTime());
            $notification->setContent(sprintf(
                'Votre réservation pour "%s" le %s a été annulée.%s',
                $booking->getSpace()->getName(),
                $booking->getDate()->format('d/m/Y'),
                $booking->getCancellationReason() ? ' Motif : '.$booking->getCancellationReason() : '',
            ));
            $this->entityManager->persist($notification);
            $this->entityManager->flush();
        } catch (\Throwable $e) {
            $this->logger->error('Échec de la création de la notification d\'annulation pour la réservation {id} : {message}', [
                'id' => (string) $booking->getId(),
                'message' => $e->getMessage(),
            ]);
        }

        return $booking;
    }
}