<?php

namespace App\Services\State\Processor\DiverCity;

use ApiPlatform\Metadata\Operation;
use ApiPlatform\State\ProcessorInterface;
use App\Entity\DiverCity\Booking;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\DependencyInjection\Attribute\Autowire;
use Symfony\Component\HttpKernel\Exception\UnprocessableEntityHttpException;

/**
 * Traite l'ajout de la source d'information ("Comment avez-vous connu notre
 * tiers-lieu ?"), renseignée par le demandeur après soumission de sa
 * réservation (PATCH /divercity/bookings/{id}/information-source).
 */
class BookingInformationSourceProcessor implements ProcessorInterface
{
    public function __construct(
        #[Autowire(service: 'api_platform.doctrine.orm.state.persist_processor')]
        private ProcessorInterface $persistProcessor,
        private Security $security,
    ) {
    }

    /**
     * @param Booking $data
     */
    public function process(mixed $data, Operation $operation, array $uriVariables = [], array $context = []): mixed
    {
        $currentUser = $this->security->getUser();
        if (!$currentUser instanceof \App\Entity\User\User) {
            throw new UnprocessableEntityHttpException('Vous devez être connecté pour effectuer cette action.');
        }

        if ($currentUser !== $data->getUser()) {
            throw new UnprocessableEntityHttpException('Seul le demandeur peut renseigner cette information.');
        }

        /** @var Booking $booking */
        $booking = $this->persistProcessor->process($data, $operation, $uriVariables, $context);

        return $booking;
    }
}