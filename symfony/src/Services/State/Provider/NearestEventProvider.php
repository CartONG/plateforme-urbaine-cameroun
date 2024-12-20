<?php

namespace App\Services\State\Provider;

use ApiPlatform\Metadata\Operation;
use ApiPlatform\State\ProviderInterface;
use App\Repository\ResourceRepository;

class NearestEventProvider implements ProviderInterface
{
    public function __construct(
        private ResourceRepository $resourceRepository,
    ) {
    }

    public function provide(Operation $operation, array $uriVariables = [], array $context = []): object|array|null
    {
        $nearestEvents = $this->resourceRepository->findNearestEvents();

        return $nearestEvents;
    }
}
