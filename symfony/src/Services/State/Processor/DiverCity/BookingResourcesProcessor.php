<?php

namespace App\Services\State\Processor\DiverCity;

use ApiPlatform\Metadata\Operation;
use ApiPlatform\State\ProcessorInterface;
use App\Entity\DiverCity\Booking;
use App\Entity\DiverCity\HighlightedResource;
use App\Repository\DiverCity\BookingRepository;
use App\Repository\DiverCity\HighlightedResourceRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\DependencyInjection\Attribute\Autowire;
use Symfony\Component\HttpKernel\Exception\ConflictHttpException;

class BookingResourcesProcessor implements ProcessorInterface
{
    public function __construct(
        #[Autowire(service: 'api_platform.doctrine.orm.state.persist_processor')]
        private ProcessorInterface $persistProcessor,
        private HighlightedResourceRepository $highlightedResourceRepository,
        private BookingRepository $bookingRepository,
        private EntityManagerInterface $entityManager,
    ) {
    }

    /**
     * @param Booking $data
     */
    public function process(mixed $data, Operation $operation, array $uriVariables = [], array $context = []): mixed
    {
        if ($data instanceof Booking) {
            foreach ($data->getResources() as $resource) {
                $conflictingBooking = $this->bookingRepository->findConflictingBookingResourceLink(
                    $resource,
                    $data->getId()
                );

                if (null !== $conflictingBooking) {
                    throw new ConflictHttpException(sprintf(
                        'La ressource "%s" est déjà liée à une autre réservation.',
                        $resource->getName()
                    ));
                }
            }
        }

        // 1. Sauvegarde des ressources liées à la réservation
        $result = $this->persistProcessor->process($data, $operation, $uriVariables, $context);

        // 2. Traitement automatique du Highlight pour chaque ressource liée
        if ($data instanceof Booking) {
            foreach ($data->getResources() as $resource) {
                $resourceId = (string) $resource->getId();

                $highlighted = $this->highlightedResourceRepository->findOneByResourceId($resourceId);

                if (!$highlighted) {
                    $highlighted = new HighlightedResource();
                    $highlighted->setResourceId($resourceId);
                }

                $highlighted->setIsHighlighted(true);
                if (null === $highlighted->getHighlightedAt()) {
                    $highlighted->setHighlightedAt(new \DateTimeImmutable());
                }

                $this->entityManager->persist($highlighted);
            }
            $this->entityManager->flush();
        }

        return $result;
    }
}