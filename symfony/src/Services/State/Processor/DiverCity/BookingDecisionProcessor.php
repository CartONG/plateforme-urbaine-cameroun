<?php

namespace App\Services\State\Processor\DiverCity;

use ApiPlatform\Metadata\Operation;
use ApiPlatform\State\ProcessorInterface;
use App\Entity\DiverCity\Booking;
use App\Entity\DiverCity\Notification;
use App\Repository\DiverCity\BookingRepository;
use Doctrine\ORM\EntityManagerInterface;
use Psr\Log\LoggerInterface;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\DependencyInjection\Attribute\Autowire;
use Symfony\Component\HttpKernel\Exception\UnprocessableEntityHttpException;
use App\Services\Mailer\DiverCity\BookingAcceptedMailer;
use App\Services\Mailer\DiverCity\BookingRejectedMailer;


/**
 * Traite la décision d'un administrateur sur une réservation
 * (PATCH /divercity/bookings/{id}).
 *
 * Applique les règles de gestion de l'étude fonctionnelle §5.1/§5.2 :
 *   - seul un statut ACCEPTEE ou REFUSEE est une décision valide ici
 *   - la réservation doit être actuellement EN_ATTENTE pour être traitée
 *     (empêche de re-décider une réservation déjà traitée, et les
 *     race conditions entre deux admins traitant la même réservation)
 *   - un refus doit obligatoirement avoir un motif
 *   - une acceptation revérifie qu'aucun conflit n'est apparu entre-temps
 *   - une réservation acceptée rend le créneau indisponible (automatique,
 *     puisque c'est justement ce que BookingRepository::hasConflictingBooking
 *     vérifie pour les prochaines demandes)
 *   - le traitant et la date de traitement sont enregistrés automatiquement
 *   - une notification de décision est envoyée au demandeur (best-effort :
 *     un échec de notification ne doit jamais annuler une décision déjà
 *     persistée avec succès)
 */
class BookingDecisionProcessor implements ProcessorInterface
{
    public function __construct(
        #[Autowire(service: 'api_platform.doctrine.orm.state.persist_processor')]
        private ProcessorInterface $persistProcessor,
        private Security $security,
        private BookingRepository $bookingRepository,
        private EntityManagerInterface $entityManager,
        private LoggerInterface $logger,
        private BookingAcceptedMailer $bookingAcceptedMailer,
        private BookingRejectedMailer $bookingRejectedMailer,
    ) {
    }

    /**
     * @param Booking $data
     */
    public function process(mixed $data, Operation $operation, array $uriVariables = [], array $context = []): mixed
    {
        $admin = $this->security->getUser();
        if (!$admin instanceof \App\Entity\User\User) {
            throw new UnprocessableEntityHttpException('Vous devez être connecté pour traiter une réservation.');
        }

        $newStatus = $data->getStatus();
        if (null === $newStatus || !in_array($newStatus->getCode(), ['ACCEPTEE', 'REFUSEE'], true)) {
            throw new UnprocessableEntityHttpException('Le statut doit être "ACCEPTEE" ou "REFUSEE" pour une décision.');
        }

        // Garde-fou anti double-décision / race condition : $data est déjà
        // hydraté avec le NOUVEAU statut à ce stade (le DeserializeListener
        // d'API Platform a appliqué le patch avant d'arriver ici). On relit
        // donc l'état ACTUEL en base pour vérifier que la réservation est
        // bien encore EN_ATTENTE avant de la traiter, afin d'empêcher :
        //   - de ré-accepter/refuser une réservation déjà décidée
        //   - deux admins qui traiteraient la même réservation en même temps
        $currentStatusCode = $this->bookingRepository->getCurrentStatusCode($data->getId());
        if ('EN_ATTENTE' !== $currentStatusCode) {
            throw new UnprocessableEntityHttpException('Cette réservation a déjà été traitée.');
        }

        // Règle §5.2 : motif obligatoire en cas de refus.
        if ('REFUSEE' === $newStatus->getCode() && empty($data->getRefusalReason())) {
            throw new UnprocessableEntityHttpException('Un motif est obligatoire en cas de refus.');
        }

        // Sécurité anti-race-condition : si on accepte, on revérifie qu'aucune
        // autre réservation n'a été validée entre-temps sur ce créneau
        // (on exclut la réservation courante de la vérification, sinon elle
        // se détecterait elle-même en conflit).
        if ('ACCEPTEE' === $newStatus->getCode()) {
            $space = $data->getSpace();
            if ($this->bookingRepository->hasConflictingBooking(
                $space,
                $data->getDate(),
                $data->getStartTime(),
                $data->getEndTime(),
                $data->getId(),
            )) {
                throw new UnprocessableEntityHttpException('Ce créneau a déjà été accepté sur une autre réservation entre-temps.');
            }
        }

        $data->setProcessingUser($admin);
        $data->setProcessedAt(new \DateTime());

        /** @var Booking $booking */
        $booking = $this->persistProcessor->process($data, $operation, $uriVariables, $context);

        // Notification de décision (règle de gestion §5.1).
        // Isolée dans un try/catch : un échec d'envoi de notification ne doit
        // jamais transformer une décision métier déjà persistée en erreur 500
        // côté client (la décision elle-même a réussi au flush précédent).
        try {
            $notification = new Notification();
            $notification->setBooking($booking);
            $notification->setUser($booking->getUser());
            $notification->setType(Notification::TYPE_DECISION);
            $notification->setSentAt(new \DateTime());
            $notification->setContent('ACCEPTEE' === $newStatus->getCode()
                ? sprintf('Votre réservation pour "%s" le %s a été acceptée.', $booking->getSpace()->getName(), $booking->getDate()->format('d/m/Y'))
                : sprintf('Votre réservation pour "%s" le %s a été refusée. Motif : %s', $booking->getSpace()->getName(), $booking->getDate()->format('d/m/Y'), $booking->getRefusalReason())
            );
            $this->entityManager->persist($notification);
            $this->entityManager->flush();
            if ('ACCEPTEE' === $newStatus->getCode()) {
                $this->bookingAcceptedMailer->send($booking);
            } else {
                $this->bookingRejectedMailer->send($booking);
            }
        } catch (\Throwable $e) {
            $this->logger->error('Échec de la création de la notification de décision pour la réservation {id} : {message}', [
                'id' => (string) $booking->getId(),
                'message' => $e->getMessage(),
            ]);
        }

        return $booking;
    }
}
