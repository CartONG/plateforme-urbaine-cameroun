<?php

namespace App\Services\State\Provider;

use ApiPlatform\Metadata\Operation;
use ApiPlatform\State\ProviderInterface;
use App\Repository\HighlightedItemRepository;

class HighlightedItemProvider implements ProviderInterface
{
    public function __construct(
        private HighlightedItemRepository $highlightedItemRepository,
    ) {
    }

    public function provide(Operation $operation, array $uriVariables = [], array $context = []): object|array|null
    {
        return $this->highlightedItemRepository->findAllHighlighted();
    }
}
