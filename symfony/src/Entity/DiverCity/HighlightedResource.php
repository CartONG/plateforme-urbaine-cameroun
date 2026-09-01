<?php

namespace App\Entity\DiverCity;

use ApiPlatform\Metadata\ApiProperty;
use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\GetCollection;
use ApiPlatform\Metadata\Patch;
use ApiPlatform\Metadata\Post;
use App\Entity\File\MediaObject;
use App\Repository\DiverCity\HighlightedResourceRepository;
use App\Security\Voter\DiverCity\SpaceScopedVoter;
use App\Services\State\Processor\DiverCity\HighlightedResourceProcessor;
use App\Services\State\Provider\DiverCity\HighlightedResourceProvider;
use App\Services\State\Provider\DiverCity\MainHighlightedResourcesProvider;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Serializer\Attribute\Groups;

/**
 * Ressources mises "à la une" sur l'espace DiverCity, sur le même principe
 * que HighlightedItem côté PDC, mais limité aux Resource liées à au moins
 * une réservation DiverCity (via Booking::$resources).
 */
#[ORM\Entity(repositoryClass: HighlightedResourceRepository::class)]
#[ORM\Table(name: 'highlighted_resource', schema: 'divercity')]
#[ApiResource(
    paginationEnabled: false,
    operations: [
        new GetCollection(
            uriTemplate: '/divercity/highlighted_resources/main',
            normalizationContext: ['groups' => [self::GET_FULL, MediaObject::READ]],
            provider: MainHighlightedResourcesProvider::class,
        ),
    ]
)]
#[ApiResource(
    paginationEnabled: false,
    security: "is_granted('".SpaceScopedVoter::MANAGE_SPACE."')",
    operations: [
        new GetCollection(
            normalizationContext: ['groups' => [self::GET_FULL, MediaObject::READ]],
            provider: HighlightedResourceProvider::class,
        ),
        new Post(
            denormalizationContext: ['groups' => [self::WRITE]],
            processor: HighlightedResourceProcessor::class,
        ),
        new Patch(
            denormalizationContext: ['groups' => [self::WRITE]],
            processor: HighlightedResourceProcessor::class,
        ),
    ]
)]
#[UniqueEntity(fields: ['resourceId'])]
class HighlightedResource
{
    public const GET_FULL = 'divercity_highlighted_resource:get:full';
    public const WRITE = 'divercity_highlighted_resource:write';

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[ApiProperty(identifier: false)]
    #[Groups([self::GET_FULL])]
    private ?int $id = null;

    // UUID de la Resource (App\Entity\Resource::id) — même principe que
    // HighlightedItem::$itemId côté PDC.
    #[ORM\Column(unique: true)]
    #[ApiProperty(identifier: true)]
    #[Groups([self::GET_FULL, self::WRITE])]
    private ?string $resourceId = null;

    #[ORM\Column]
    #[Groups([self::GET_FULL, self::WRITE])]
    #[Gedmo\SortableGroup]
    private ?bool $isHighlighted = null;

    #[ORM\Column(nullable: true)]
    #[Groups([self::GET_FULL])]
    private ?\DateTimeImmutable $highlightedAt = null;

    #[ORM\Column(nullable: true)]
    #[Groups([self::GET_FULL, self::WRITE])]
    #[Gedmo\SortablePosition]
    private ?int $position = null;

    // Champs "virtuels" peuplés par les providers à partir de la Resource liée.
    #[Groups([self::GET_FULL])]
    private ?string $name = null;

    #[Groups([self::GET_FULL])]
    private ?string $description = null;

    #[Groups([self::GET_FULL])]
    private ?string $slug = null;

    #[Groups([self::GET_FULL])]
    private ?MediaObject $image = null;

    #[Groups([self::GET_FULL])]
    private ?string $link = null;

    #[Groups([self::GET_FULL])]
    public $updatedAt;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getResourceId(): ?string
    {
        return $this->resourceId;
    }

    public function setResourceId(string $resourceId): static
    {
        $this->resourceId = $resourceId;

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

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(?string $name): static
    {
        $this->name = $name;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): static
    {
        $this->description = $description;

        return $this;
    }

    public function getSlug(): ?string
    {
        return $this->slug;
    }

    public function setSlug(?string $slug): static
    {
        $this->slug = $slug;

        return $this;
    }

    public function getImage(): ?MediaObject
    {
        return $this->image;
    }

    public function setImage(?MediaObject $image): static
    {
        $this->image = $image;

        return $this;
    }

    public function getLink(): ?string
    {
        return $this->link;
    }

    public function setLink(?string $link): static
    {
        $this->link = $link;

        return $this;
    }

    public function getUpdatedAt(): ?\DateTimeInterface
    {
        return $this->updatedAt;
    }

    public function setUpdatedAt(\DateTimeInterface $updatedAt): static
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }
}