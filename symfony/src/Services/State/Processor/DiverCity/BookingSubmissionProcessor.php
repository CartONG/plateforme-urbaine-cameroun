<?php

namespace App\Services\State\Processor\DiverCity;

use ApiPlatform\Metadata\Operation;
use ApiPlatform\State\ProcessorInterface;
use App\Entity\DiverCity\Booking;
use App\Repository\DiverCity\BlockedPeriodRepository;
use App\Repository\DiverCity\BookingRepository;
use App\Repository\DiverCity\StatusRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\DependencyInjection\Attribute\Autowire;
use Symfony\Component\HttpKernel\Exception\UnprocessableEntityHttpException;

/**
 * Traite la soumission d'une nouvelle demande de réservation (POST /divercity/bookings).
 *
 *   - le demandeur doit être un utilisateur connecté (pas de compte = pas de réservation)
 *   - le créneau ne doit pas être dans le passé
 *   - le créneau ne doit pas être déjà occupé par une réservation acceptée
 *   - le créneau ne doit pas tomber dans une période bloquée par un admin
 *   - le nombre de participants ne doit pas dépasser la capacité de l'espace
 *   - le statut initial est toujours "En attente"
 *   - une notification de soumission est envoyée au demandeur (mail / WhatsApp)
 */
class BookingSubmissionProcessor implements ProcessorInterface
{
    public function __construct(
        #[Autowire(service: 'api_platform.doctrine.orm.state.persist_processor')]
        private ProcessorInterface $persistProcessor,
        private Security $security,
        private BookingRepository $bookingRepository,
        private BlockedPeriodRepository $blockedPeriodRepository,
        private StatusRepository $statusRepository,
        private EntityManagerInterface $entityManager,
        // TODO: brancher ici ton service d'envoi réel (mailer Symfony et/ou client WhatsApp Business API).
        // private NotificationDispatcherInterface $notificationDispatcher,
    ) {
    }

    /**
     * @param Booking $data
     */
    public function process(mixed $data, Operation $operation, array $uriVariables = [], array $context = []): mixed
    {
        // 1. Le demandeur est l'utilisateur connecté, jamais saisi par le front.
        $currentUser = $this->security->getUser();
        if (!$currentUser instanceof \App\Entity\User\User) {
            throw new UnprocessableEntityHttpException('Vous devez être connecté pour effectuer une réservation.');
        }
        $data->setUser($currentUser);

        $space = $data->getSpace();
        if (null === $space) {
            throw new UnprocessableEntityHttpException('L\'espace à réserver est obligatoire.');
        }

        // 2. Le créneau demandé ne doit pas être dans le passé, et doit respecter
        //    un délai minimum de 48h pour permettre le traitement de la demande.
        $date = $data->getDate();
        $startTime = $data->getStartTime();

        if (null === $date || null === $startTime) {
            throw new UnprocessableEntityHttpException('La date et l\'heure de début sont obligatoires.');
        }

        $bookingDateTime = \DateTimeImmutable::createFromFormat(
            'Y-m-d H:i:s',
            $date->format('Y-m-d').' '.$startTime->format('H:i:s'),
        );

        if (false === $bookingDateTime) {
            throw new UnprocessableEntityHttpException('La date ou l\'heure de la réservation est invalide.');
        }

        $now = new \DateTimeImmutable();
        $minimumBookingDateTime = $now->modify('+48 hours');

        if ($bookingDateTime < $now) {
            throw new UnprocessableEntityHttpException('Impossible de réserver un créneau déjà passé.');
        }

        if ($bookingDateTime < $minimumBookingDateTime) {
            throw new UnprocessableEntityHttpException(sprintf(
                'Les réservations doivent être effectuées au moins 48h à l\'avance. Le créneau le plus proche disponible est le %s.',
                $minimumBookingDateTime->format('d/m/Y à H:i'),
            ));
        }

        // 3. Vérifie que le créneau n'est pas déjà pris par une réservation acceptée.
        if ($this->bookingRepository->hasConflictingBooking(
            $space,
            $data->getDate(),
            $data->getStartTime(),
            $data->getEndTime(),
        )) {
            throw new UnprocessableEntityHttpException('Ce créneau est déjà réservé.');
        }

        // 4. Vérifie que le créneau ne tombe pas dans une période bloquée par un admin.
        if ($this->blockedPeriodRepository->isPeriodBlocked(
            $space,
            $data->getDate(),
            $data->getStartTime(),
            $data->getEndTime(),
        )) {
            throw new UnprocessableEntityHttpException('Ce créneau est indisponible.');
        }

        // 5. Vérifie que le nombre de participants respecte la capacité de l'espace.
        if ($data->getParticipantCount() > $space->getMaxCapacity()) {
            throw new UnprocessableEntityHttpException(sprintf(
                'Le nombre de participants (%d) dépasse la capacité maximale de l\'espace (%d).',
                $data->getParticipantCount(),
                $space->getMaxCapacity(),
            ));
        }

        // 6. Statut initial obligatoire : "En attente".
        $pendingStatus = $this->statusRepository->findOneBy(['code' => 'EN_ATTENTE']);
        if (null === $pendingStatus) {
            throw new \RuntimeException('Le statut "EN_ATTENTE" est introuvable en base. Vérifiez le seed de la table divercity.status.');
        }
        $data->setStatus($pendingStatus);

        // 7. Persistance de la réservation + notification dans une seule transaction,
        //    pour garantir qu'aucune réservation "orpheline" (sans notification) n'existe en base
        //    si une étape échoue après l'autre.
        return $this->entityManager->wrapInTransaction(function () use ($data, $operation, $uriVariables, $context, $space, $currentUser) {
            /** @var Booking $booking */
            $booking = $this->persistProcessor->process($data, $operation, $uriVariables, $context);

            $notification = new Notification();
            $notification->setBooking($booking);
            $notification->setUser($currentUser);
            $notification->setType(Notification::TYPE_SUBMISSION);
            $notification->setContent(sprintf(
                'Votre demande de réservation pour "%s" le %s a bien été enregistrée. Elle est en attente de traitement.',
                $space->getName(),
                $data->getDate()->format('d/m/Y'),
            ));

            // 8. Envoi réel de la notification (mail et/ou WhatsApp).
            //    IMPORTANT : sentAt ne doit être rempli QUE si l'envoi a effectivement réussi.
            //    Tant que le vrai service d'envoi n'est pas branché ici, on horodate à la
            //    persistance pour ne pas bloquer la colonne NOT NULL — à remplacer dès que
            //    le dispatcher réel est disponible (voir TODO dans le constructeur).
            //
            // Exemple d'intégration une fois le service prêt :
            //
            // try {
            //     $this->notificationDispatcher->send($notification, $currentUser);
            //     $notification->setSentAt(new \DateTimeImmutable());
            // } catch (\Throwable $e) {
            //     // log l'échec d'envoi, mais ne bloque pas la réservation :
            //     // la notification reste en base avec sentAt renseigné à défaut,
            //     // ou passe par une colonne "status" (PENDING/SENT/FAILED) si tu veux
            //     // distinguer proprement "créée" de "réellement envoyée".
            // }
            $notification->setSentAt(new \DateTimeImmutable());

            $this->entityManager->persist($notification);
            $this->entityManager->flush();

            return $booking;
        });
    }
}
