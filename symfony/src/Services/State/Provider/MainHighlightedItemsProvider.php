<?php

namespace App\Services\State\Provider;

use ApiPlatform\Metadata\Operation;
use ApiPlatform\State\ProviderInterface;
use App\Repository\ActorRepository;
use App\Repository\HighlightedItemRepository;
use App\Repository\ProjectRepository;
use App\Repository\ResourceRepository;

class MainHighlightedItemsProvider implements ProviderInterface
{
    public function __construct(
        private ProjectRepository $projectRepository,
        private ActorRepository $actorRepository,
        private ResourceRepository $resourceRepository,
        private HighlightedItemRepository $highlightedItemRepository,
    ) {
    }

    public function provide(Operation $operation, array $uriVariables = [], array $context = []): object|array|null
    {
        $highlightedItemsItemIds = $this->highlightedItemRepository->findMainHighlightedItemIds();
        $items = array_merge(
            $this->projectRepository->findHighlightedItems($highlightedItemsItemIds),
            $this->resourceRepository->findHighlightedItems($highlightedItemsItemIds),
            $this->actorRepository->findHighlightedItems($highlightedItemsItemIds),
        );

        return $items;
    }
}
