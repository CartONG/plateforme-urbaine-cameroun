<?php

namespace App\Repository;

use App\Entity\HighlightedItem;
use App\Enum\ItemType;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<HighlightedItem>
 */
class HighlightedItemRepository extends ServiceEntityRepository
{
    public function __construct(
        ManagerRegistry $registry,
        private ProjectRepository $projectRepository,
        private ActorRepository $actorRepository,
        private ResourceRepository $resourceRepository,
    ) {
        parent::__construct($registry, HighlightedItem::class);
    }

    public function findMainHighlightedItemIds(): array
    {
        return array_map(fn ($item) => $item->getItemId(), $this->findMainHighlightedItem());
    }

    public function findMainHighlightedItem(): array
    {
        return $this->createQueryBuilder('h')
            ->select('h')
            ->where('h.isHighlighted = true')
            ->andWhere('h.position < 3')
            ->orderBy('h.position', 'ASC')
            ->getQuery()
            ->getResult();
    }

    public function findAllHighlighted(): array
    {
        $partialHighlightedItems = $this->findAll();
        $ids = [
            ItemType::PROJECT->value => [],
            ItemType::ACTOR->value => [],
            ItemType::RESOURCE->value => [],
        ];
        foreach ($partialHighlightedItems as $highlightedItem) {
            if ($highlightedItem->getIsHighlighted()) {
                $ids[$highlightedItem->getItemType()->value][] = $highlightedItem->getItemId();
            }
        }

        $highlightedProjects = $this->projectRepository->findBy(['id' => $ids[ItemType::PROJECT->value]]);
        $highlightedActors = $this->actorRepository->findBy(['id' => $ids[ItemType::ACTOR->value]]);
        $highlightedResources = $this->resourceRepository->findBy(['id' => $ids[ItemType::RESOURCE->value]]);

        $highlightedItems = array_merge($highlightedProjects, $highlightedActors, $highlightedResources);
        foreach ($partialHighlightedItems as $key => $highlightedItem) {
            $name = null;
            foreach ($highlightedItems as $item) {
                $id = is_string($item->getId()) ? $item->getId() : $item->getId()->toString();
                if ($id === $highlightedItem->getItemId()) {
                    $name = $item->getName();
                    break;
                }
            }
            $partialHighlightedItems[$key]->setName($name);
        }

        return $partialHighlightedItems;
    }

    public function getMaxPosition(): int
    {
        return $this->createQueryBuilder('h')
            ->select('MAX(h.position)')
            ->getQuery()
            ->getSingleScalarResult() ?? 0;
    }

    //    /**
    //     * @return HighlightedItem[] Returns an array of HighlightedItem objects
    //     */
    //    public function findByExampleField($value): array
    //    {
    //        return $this->createQueryBuilder('h')
    //            ->andWhere('h.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->orderBy('h.id', 'ASC')
    //            ->setMaxResults(10)
    //            ->getQuery()
    //            ->getResult()
    //        ;
    //    }

    //    public function findOneBySomeField($value): ?HighlightedItem
    //    {
    //        return $this->createQueryBuilder('h')
    //            ->andWhere('h.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}
