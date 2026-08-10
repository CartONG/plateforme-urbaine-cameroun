<?php

namespace App\Services\State\Processor\DiverCity;

use ApiPlatform\Metadata\Operation;
use ApiPlatform\State\ProcessorInterface;
use App\Entity\DiverCity\Booking;
use App\Entity\DiverCity\Notification;
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
 * Applique les règles de gestion de l'étude fonctionnelle §5.1/§5.2 :
 *   - le demandeur doit être un utilisateur connecté (pas de compte = pas de réservation)
 *   - le créneau ne doit pas être déjà occupé par une réservation acceptée
 *   - le créneau ne doit pas tomber dans une période bloquée par un admin
 *   - le nombre de participants ne doit pas dépasser la capacité de l'espace
 *   - le statut initial est toujours "En attente"
 *   - une notification de soumission est envoyée au demandeur
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
    ) {
    }

    /**
     * @param Booking $data
     */
    public function process(mixed $data, Operation $operation, array $uriVariables = [], array $context = []): mixed
    {
        // 1. Le demandeur est l'utilisateur connecté, jamais saisi par le front.
        //    (Security::getUser() renvoie null si personne n'est authentifié ;
        //    la route est de toute façon protégée par IS_AUTHENTICATED_FULLY
        //    dans Booking::class, donc ce cas ne devrait normalement pas arriver,
        //    mais on le garde par sécurité défensive.)
        $currentUser = $this->security->getUser();
        if (!$currentUser instanceof \App\Entity\User\User) {
            throw new UnprocessableEntityHttpException('Vous devez être connecté pour effectuer une réservation.');
        }
        $data->setUser($currentUser);

        $space = $data->getSpace();
        if (null === $space) {
            throw new UnprocessableEntityHttpException('L\'espace à réserver est obligatoire.');
        }

        // 2. Vérifie que le créneau n'est pas déjà pris par une réservation acceptée.
        if ($this->bookingRepository->hasConflictingBooking(
            $space,
            $data->getDate(),
            $data->getStartTime(),
            $data->getEndTime(),
        )) {
            throw new UnprocessableEntityHttpException('Ce créneau est déjà réservé.');
        }

        // 3. Vérifie que le créneau ne tombe pas dans une période bloquée par un admin.
        if ($this->blockedPeriodRepository->isPeriodBlocked(
            $space,
            $data->getDate(),
            $data->getStartTime(),
            $data->getEndTime(),
        )) {
            throw new UnprocessableEntityHttpException('Ce créneau est indisponible.');
        }

        // 4. Vérifie que le nombre de participants respecte la capacité de l'espace.
        if ($data->getParticipantCount() > $space->getMaxCapacity()) {
            throw new UnprocessableEntityHttpException(sprintf(
                'Le nombre de participants (%d) dépasse la capacité maximale de l\'espace (%d).',
                $data->getParticipantCount(),
                $space->getMaxCapacity(),
            ));
        }

        // 5. Statut initial obligatoire : "En attente".
        $pendingStatus = $this->statusRepository->findOneBy(['code' => 'EN_ATTENTE']);
        if (null === $pendingStatus) {
            // Erreur de configuration serveur (le seed des statuts n'a pas été joué), pas une erreur utilisateur.
            throw new \RuntimeException('Le statut "EN_ATTENTE" est introuvable en base. Vérifiez le seed de la table divercity.status.');
        }
        $data->setStatus($pendingStatus);

        // 6. Persistance de la réservation via le processor natif d'API Platform.
        /** @var Booking $booking */
        $booking = $this->persistProcessor->process($data, $operation, $uriVariables, $context);

        // 7. Notification de soumission (règle de gestion §5.1).
        $notification = new Notification();
        $notification->setBooking($booking);
        $notification->setUser($currentUser);
        $notification->setType(Notification::TYPE_SUBMISSION);
        $notification->setContent(sprintf(
            'Votre demande de réservation pour "%s" le %s a bien été enregistrée. Elle est en attente de traitement.',
            $space->getName(),
            $data->getDate()->format('d/m/Y'),
        ));
        $this->entityManager->persist($notification);
        $this->entityManager->flush();

        return $booking;
    }
}