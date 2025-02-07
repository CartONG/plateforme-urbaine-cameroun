<?php

namespace App\Services\State\Processor\Common;

use ApiPlatform\Metadata\Operation;
use ApiPlatform\State\ProcessorInterface;
use App\Entity\HighlightedItem;
use App\Enum\ItemType;
use App\Repository\ActorRepository;
use App\Repository\HighlightedItemRepository;
use App\Repository\ProjectRepository;
use App\Repository\ResourceRepository;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\DependencyInjection\Attribute\Autowire;
use Symfony\Component\HttpFoundation\JsonResponse;

class HighlightedItemProcessor implements ProcessorInterface
{
    public function __construct(
        #[Autowire(service: 'api_platform.doctrine.orm.state.persist_processor')]
        private ProcessorInterface $persistProcessor,
        private Security $security,
        private HighlightedItemRepository $highlightedItemRepository,
        private ProjectRepository $projectRepository,
        private ActorRepository $actorRepository,
        private ResourceRepository $resourceRepository,
    ) {
    }

    public function process(mixed $data, Operation $operation, array $uriVariables = [], array $context = []): mixed
    {
        if (!$data instanceof HighlightedItem) {
            return $data;
        }

        $itemType = $this->getItemType($data->getItemId());
        if (null === $itemType) {
            return new JsonResponse(['message' => 'Item not found'], JsonResponse::HTTP_NOT_FOUND);
        }

        $data->setItemType($itemType);
        if ($data->getIsHighlighted()) {
            $data->setHighlightedAt(new \DateTimeImmutable());
        }

        return $this->persistProcessor->process($data, $operation, $uriVariables, $context);
    }

    private function getItemType(string $itemId): ?ItemType
    {
        $project = $this->projectRepository->find($itemId);
        $actor = $this->actorRepository->find($itemId);
        $resource = $this->resourceRepository->find($itemId);
        if ($project) {
            return ItemType::PROJECT;
        } elseif ($actor) {
            return ItemType::ACTOR;
        } elseif ($resource) {
            return ItemType::RESOURCE;
        } else {
            return null;
        }
    }
}
