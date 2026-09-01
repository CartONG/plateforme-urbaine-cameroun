<?php

namespace App\Services\State\Processor\DiverCity;

use ApiPlatform\Metadata\Operation;
use ApiPlatform\State\ProcessorInterface;
use App\Entity\DiverCity\Booking;
use App\Entity\DiverCity\HighlightedResource;
use App\Repository\DiverCity\HighlightedResourceRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\DependencyInjection\Attribute\Autowire;

class BookingResourcesProcessor implements ProcessorInterface
{
    public function __construct(
        #[Autowire(service: 'api_platform.doctrine.orm.state.persist_processor')]
        private ProcessorInterface $persistProcessor,
        private HighlightedResourceRepository $highlightedResourceRepository,
        private EntityManagerInterface $entityManager,
    ) {
    }

    /**
     * @param Booking $data
     */
    public function process(mixed $data, Operation $operation, array $uriVariables = [], array $context = []): mixed
    {
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

                // Force l'activation à la une et initialise la date si nécessaire
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
