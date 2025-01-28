<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiProperty;
use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\GetCollection;
use ApiPlatform\Metadata\Patch;
use ApiPlatform\Metadata\Post;
use App\Enum\ItemType;
use App\Repository\HighlightedItemRepository;
use App\Services\State\Processor\Common\HighlightedItemProcessor;
use App\Services\State\Provider\HighlightedItemProvider;
use App\Services\State\Provider\MainHighlightedItemsProvider;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Serializer\Attribute\Groups;

#[ORM\Entity(repositoryClass: HighlightedItemRepository::class)]
#[ApiResource(
    paginationEnabled: false,
    operations: [
        new GetCollection(
            uriTemplate: '/highlighted_items/main',
            normalizationContext: ['groups' => [HighlightedItem::GET_FULL]],
            provider: MainHighlightedItemsProvider::class,
        ),
    ]
)]
#[ApiResource(
    paginationEnabled: false,
    security: "is_granted('ROLE_ADMIN')",
    operations: [
        new GetCollection(
            normalizationContext: ['groups' => [HighlightedItem::GET_FULL]],
            provider: HighlightedItemProvider::class,
        ),
        new Post(
            denormalizationContext: ['groups' => [HighlightedItem::WRITE]],
            processor: HighlightedItemProcessor::class,
        ),
        new Patch(
            denormalizationContext: ['groups' => [HighlightedItem::WRITE]],
            processor: HighlightedItemProcessor::class,
        ),
    ]
)]
#[UniqueEntity(fields: ['itemId'])]
class HighlightedItem
{
    public const GET_FULL = 'highlighted_item:get:full';
    public const WRITE = 'highlighted_item:write';

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[ApiProperty(identifier: false)]
    #[Groups([HighlightedItem::GET_FULL])]
    private ?int $id = null;

    #[ORM\Column(unique: true)]
    #[ApiProperty(identifier: true)]
    #[Groups([HighlightedItem::GET_FULL, HighlightedItem::WRITE])]
    private ?string $itemId = null;

    #[ORM\Column]
    #[Groups([HighlightedItem::GET_FULL, HighlightedItem::WRITE])]
    #[Gedmo\SortableGroup]
    private ?bool $isHighlighted = null;

    #[ORM\Column(nullable: true)]
    #[Groups([HighlightedItem::GET_FULL])]
    private ?\DateTimeImmutable $highlightedAt = null;

    #[ORM\Column(nullable: true)]
    #[Groups([HighlightedItem::GET_FULL, HighlightedItem::WRITE])]
    #[Gedmo\SortablePosition]
    private ?int $position = null;

    #[ORM\Column(enumType: ItemType::class)]
    #[Groups([HighlightedItem::GET_FULL])]
    private ?ItemType $itemType = null;

    #[Groups([HighlightedItem::GET_FULL])]
    private ?string $name = null;

    public string $description;
    public $updatedAt;
    public $type;
    public $link;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(?string $name): void
    {
        $this->name = $name;
    }

    public function getItemId(): ?string
    {
        return $this->itemId;
    }

    public function setItemId(string $itemId): static
    {
        $this->itemId = $itemId;

        return $this;
    }

    public function getIsHighlighted(): ?bool
    {
        return $this->isHighlighted;
    }

    public function setIsHighlighted(bool $isHighlighted): static
    {
        $this->isHighlighted = $isHighlighted;

        return $this;
    }

    public function getHighlightedAt(): ?\DateTimeImmutable
    {
        return $this->highlightedAt;
    }

    public function setHighlightedAt(?\DateTimeImmutable $highlightedAt): static
    {
        $this->highlightedAt = $highlightedAt;

        return $this;
    }

    public function getPosition(): ?int
    {
        return $this->position;
    }

    public function setPosition(?int $position): static
    {
        $this->position = $position;

        return $this;
    }

    public function getItemType(): ?ItemType
    {
        return $this->itemType;
    }

    public function setItemType(ItemType $itemType): static
    {
        $this->itemType = $itemType;

        return $this;
    }
}
