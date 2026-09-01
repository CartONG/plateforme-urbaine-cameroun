<?php

namespace App\Services\State\Processor\DiverCity;

use ApiPlatform\Metadata\Operation;
use ApiPlatform\State\ProcessorInterface;
use App\Entity\DiverCity\HighlightedResource;
use App\Repository\DiverCity\HighlightedResourceRepository;
use Symfony\Component\DependencyInjection\Attribute\Autowire;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;

class HighlightedResourceProcessor implements ProcessorInterface
{
    public function __construct(
        #[Autowire(service: 'api_platform.doctrine.orm.state.persist_processor')]
        private ProcessorInterface $persistProcessor,
        private HighlightedResourceRepository $highlightedResourceRepository,
    ) {
    }

    /**
     * @param HighlightedResource $data
     */
    public function process(mixed $data, Operation $operation, array $uriVariables = [], array $context = []): mixed
    {
        $resourceId = $data->getResourceId();

        if (!$resourceId) {
            throw new BadRequestHttpException('Le paramètre resourceId est requis.');
        }

        $existing = $this->highlightedResourceRepository->findOneByResourceId($resourceId);

        if ($existing) {
            $entity = $existing;
            if (null !== $data->getIsHighlighted()) {
                $entity->setIsHighlighted($data->getIsHighlighted());
            }
            if (null !== $data->getPosition()) {
                $entity->setPosition($data->getPosition());
            }
        } else {
            $entity = $data;
        }

        if ($entity->getIsHighlighted()) {
            if (null === $entity->getHighlightedAt()) {
                $entity->setHighlightedAt(new \DateTimeImmutable());
            }
        } else {
            $entity->setHighlightedAt(null);
        }

        return $this->persistProcessor->process($entity, $operation, $uriVariables, $context);
    }
}