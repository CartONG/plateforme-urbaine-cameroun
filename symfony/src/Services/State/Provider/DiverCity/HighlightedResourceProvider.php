<?php

namespace App\Services\State\Provider\DiverCity;

use ApiPlatform\Metadata\Operation;
use ApiPlatform\State\ProviderInterface;
use App\Repository\DiverCity\HighlightedResourceRepository;
use App\Repository\ResourceRepository;

class HighlightedResourceProvider implements ProviderInterface
{
    public function __construct(
        private HighlightedResourceRepository $highlightedResourceRepository,
        private ResourceRepository $resourceRepository,
    ) {
    }

    public function provide(Operation $operation, array $uriVariables = [], array $context = []): iterable
    {
        $items = $this->highlightedResourceRepository->findAllOrderedByPosition();

        foreach ($items as $item) {
            $resource = $this->resourceRepository->find($item->getResourceId());
            if (null === $resource) {
                continue;
            }
            $item->setName($resource->getName());
            $item->setDescription($resource->getDescription());
            $item->setSlug(null); // Resource n'a pas de slug dans le fichier fourni
            $item->setImage($resource->getPreviewImage());
            $item->setLink($resource->getLink());
            $item->setUpdatedAt($resource->getUpdatedAt() ?? new \DateTimeImmutable());
        }

        return $items;
    }
}