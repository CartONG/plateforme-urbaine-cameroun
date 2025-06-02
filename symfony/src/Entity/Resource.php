<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiProperty;
use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Delete;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\GetCollection;
use ApiPlatform\Metadata\Patch;
use ApiPlatform\Metadata\Post;
use ApiPlatform\Metadata\QueryParameter;
use App\Entity\File\FileObject;
use App\Entity\File\MediaObject;
use App\Entity\Trait\BlameableEntity;
use App\Entity\Trait\CreatorMessageEntity;
use App\Entity\Trait\LocalizableEntity;
use App\Entity\Trait\TimestampableEntity;
use App\Entity\Trait\ValidateableEntity;
use App\Enum\ResourceFormat;
use App\Enum\ResourceType;
use App\Model\Enums\UserRoles;
use App\Repository\ResourceRepository;
use App\Services\State\Processor\ResourceProcessor;
use App\Services\State\Provider\NearestEventProvider;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Attribute\Groups;
use Symfony\Component\Uid\Uuid;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: ResourceRepository::class)]
#[ApiResource(
    paginationEnabled: false,
    operations: [
        new GetCollection(
            normalizationContext: ['groups' => [self::GET_FULL]],
            parameters: [
                'order[:property]' => new QueryParameter(filter: 'offer.order_filter'),
            ]
        ),
        new Get(
            normalizationContext: ['groups' => [self::GET_FULL]],
        ),
        new GetCollection(
            uriTemplate: '/resources/events/nearest',
            provider: NearestEventProvider::class,
            normalizationContext: ['groups' => [self::GET_FULL]]
        ),
    ]
)]
#[ApiResource(
    operations: [
        new Post(
            security: UserRoles::IS_GRANTED_EDITOR_RESSOURCES,
            processor: ResourceProcessor::class
        ),
        new Patch(
            security: 'is_granted("ROLE_ADMIN") or object.getCreatedBy() == user',
            processor: ResourceProcessor::class
        ),
        new Delete(
            security: 'is_granted("ROLE_ADMIN") or object.getCreatedBy() == user',
        ),
    ],
    normalizationContext: ['groups' => [self::GET_FULL, Admin1Boundary::GET_WITH_GEOM, Admin2Boundary::GET_WITH_GEOM, Admin3Boundary::GET_WITH_GEOM]],
    denormalizationContext: ['groups' => [self::WRITE]],
)]
class Resource
{
    use TimestampableEntity;
    use BlameableEntity;
    use ValidateableEntity;
    use LocalizableEntity;
    use CreatorMessageEntity;

    public const GET_FULL = 'resource:get:full';
    public const WRITE = 'resource:write';

    #[ORM\Id]
    #[ORM\Column(type: 'uuid', unique: true)]
    #[ORM\GeneratedValue(strategy: 'CUSTOM')]
    #[ORM\CustomIdGenerator('doctrine.uuid_generator')]
    #[Groups([self::GET_FULL])]
    private ?Uuid $id = null;

    #[ORM\Column(length: 255)]
    #[Groups([self::GET_FULL, self::WRITE])]
    #[Assert\NotBlank]
    private ?string $name = null;

    #[ORM\Column(type: Types::TEXT)]
    #[Groups([self::GET_FULL, self::WRITE])]
    #[Assert\NotBlank]
    #[Assert\Length(max: 500)]
    private ?string $description = null;

    #[ORM\Column(enumType: ResourceFormat::class)]
    #[Groups([self::GET_FULL, self::WRITE])]
    private ?ResourceFormat $format = null;

    /**
     * @var Collection<int, Thematic>
     */
    #[ORM\ManyToMany(targetEntity: Thematic::class, inversedBy: 'resources')]
    #[Groups([self::GET_FULL, self::WRITE])]
    private Collection $thematics;

    #[ORM\Column(length: 255, nullable: true)]
    #[Groups([self::GET_FULL, self::WRITE])]
    #[Assert\Url(protocols: ['https'])]
    private ?string $link = null;

    #[ORM\Column(enumType: ResourceType::class)]
    #[Groups([self::GET_FULL, self::WRITE])]
    private ?ResourceType $type = null;

    #[ORM\Column(nullable: true)]
    #[Groups([self::GET_FULL, self::WRITE])]
    private ?\DateTimeImmutable $startAt = null;

    #[ORM\Column(nullable: true)]
    #[Groups([self::GET_FULL, self::WRITE])]
    private ?\DateTimeImmutable $endAt = null;

    #[ORM\ManyToOne(targetEntity: FileObject::class)]
    #[Groups([self::GET_FULL, self::WRITE])]
    private ?FileObject $file = null;

    #[ORM\Column(length: 255, nullable: true)]
    #[Groups([self::GET_FULL, self::WRITE])]
    private ?string $author = null;

    #[ORM\OneToOne(targetEntity: MediaObject::class, cascade: ['remove'], orphanRemoval: true)]
    #[ApiProperty(types: ['https://schema.org/image'])]
    #[Groups([self::GET_FULL, self::WRITE])]
    private ?MediaObject $previewImage = null;

    #[ORM\Column(length: 255, nullable: true)]
    #[Groups([self::GET_FULL, self::WRITE])]
    private ?string $otherType = null;

    #[ORM\Column(length: 255, nullable: true)]
    #[Groups([self::GET_FULL, self::WRITE])]
    private ?string $otherThematic = null;
    /**
     * @var Collection<int, Admin1Boundary>
     */
    #[ORM\ManyToMany(targetEntity: Admin1Boundary::class)]
    #[Groups([self::GET_FULL, self::WRITE])]
    private Collection $admin1List;

    /**
     * @var Collection<int, Admin2Boundary>
     */
    #[ORM\ManyToMany(targetEntity: Admin2Boundary::class)]
    #[Groups([self::GET_FULL, self::WRITE])]
    private Collection $admin2List;

    /**
     * @var Collection<int, Admin3Boundary>
     */
    #[ORM\ManyToMany(targetEntity: Admin3Boundary::class)]
    #[Groups([self::GET_FULL, self::WRITE])]
    private Collection $admin3List;

    public function __construct()
    {
        $this->thematics = new ArrayCollection();
        $this->admin1List = new ArrayCollection();
        $this->admin2List = new ArrayCollection();
        $this->admin3List = new ArrayCollection();
    }

    public function getId(): ?string
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): static
    {
        $this->name = $name;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): static
    {
        $this->description = $description;

        return $this;
    }

    public function getFile(): ?FileObject
    {
        return $this->file;
    }

    public function setFile(?FileObject $file): static
    {
        $this->file = $file;

        return $this;
    }

    public function getFormat(): ?ResourceFormat
    {
        return $this->format;
    }

    public function setFormat(ResourceFormat $format): static
    {
        $this->format = $format;

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

    /**
     * @return Collection<int, Thematic>
     */
    public function getThematics(): Collection
    {
        return $this->thematics;
    }

    public function addThematic(Thematic $thematic): static
    {
        if (!$this->thematics->contains($thematic)) {
            $this->thematics->add($thematic);
        }

        return $this;
    }

    public function removeThematic(Thematic $thematic): static
    {
        $this->thematics->removeElement($thematic);

        return $this;
    }

    public function getType(): ?ResourceType
    {
        return $this->type;
    }

    public function setType(ResourceType $type): static
    {
        $this->type = $type;

        return $this;
    }

    public function getStartAt(): ?\DateTimeImmutable
    {
        return $this->startAt;
    }

    public function setStartAt(?\DateTimeImmutable $startAt): static
    {
        $this->startAt = $startAt;

        return $this;
    }

    public function getEndAt(): ?\DateTimeImmutable
    {
        return $this->endAt;
    }

    public function setEndAt(?\DateTimeImmutable $endAt): static
    {
        $this->endAt = $endAt;

        return $this;
    }

    public function getAuthor(): ?string
    {
        return $this->author;
    }

    public function setAuthor(?string $author): static
    {
        $this->author = $author;

        return $this;
    }

    public function getPreviewImage(): ?MediaObject
    {
        return $this->previewImage;
    }

    public function setPreviewImage(?MediaObject $previewImage): static
    {
        $this->previewImage = $previewImage;

        return $this;
    }

    public function getOtherType(): ?string
    {
        return $this->otherType;
    }

    public function setOtherType(?string $otherType): static
    {
        $this->otherType = $otherType;

        return $this;
    }

    /**
     * @return Collection<int, Admin1Boundary>
     */
    public function getAdmin1List(): Collection
    {
        return $this->admin1List;
    }

    public function addAdmin1List(Admin1Boundary $admin1List): static
    {
        if (!$this->admin1List->contains($admin1List)) {
            $this->admin1List->add($admin1List);
        }

        return $this;
    }

    public function removeAdmin1List(Admin1Boundary $admin1List): static
    {
        $this->admin1List->removeElement($admin1List);

        return $this;
    }

    public function getOtherThematic(): ?string
    {
        return $this->otherThematic;
    }

    public function setOtherThematic(?string $otherThematic): static
    {
        $this->otherThematic = $otherThematic;

        return $this;
    }

    /**
     * @return Collection<int, Admin2Boundary>
     */
    public function getAdmin2List(): Collection
    {
        return $this->admin2List;
    }

    public function addAdmin2List(Admin2Boundary $admin2List): static
    {
        if (!$this->admin2List->contains($admin2List)) {
            $this->admin2List->add($admin2List);
        }

        return $this;
    }

    public function removeAdmin2List(Admin2Boundary $admin2List): static
    {
        $this->admin2List->removeElement($admin2List);

        return $this;
    }

    /**
     * @return Collection<int, Admin3Boundary>
     */
    public function getAdmin3List(): Collection
    {
        return $this->admin3List;
    }

    public function addAdmin3List(Admin3Boundary $admin3List): static
    {
        if (!$this->admin3List->contains($admin3List)) {
            $this->admin3List->add($admin3List);
        }

        return $this;
    }

    public function removeAdmin3List(Admin3Boundary $admin3List): static
    {
        $this->admin3List->removeElement($admin3List);

        return $this;
    }
}
