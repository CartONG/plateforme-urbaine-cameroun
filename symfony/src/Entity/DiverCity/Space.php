<?php

namespace App\Entity\DiverCity;

use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Delete;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\GetCollection;
use ApiPlatform\Metadata\Patch;
use ApiPlatform\Metadata\Post;
use ApiPlatform\Metadata\Put;
use App\Entity\File\MediaObject;
use App\Entity\GeoData;
use App\Entity\Trait\BlameableEntity;
use App\Entity\Trait\LocalizableEntity;
use App\Entity\Trait\SluggableEntity;
use App\Entity\Trait\TimestampableEntity;
use App\Entity\Trait\ValidateableEntity;
use App\Entity\User\User;
use App\Repository\DiverCity\SpaceRepository;
use App\Security\Voter\DiverCity\SpaceScopedVoter;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Symfony\Component\Serializer\Attribute\Groups;
use Symfony\Component\Uid\Uuid;
use Symfony\Component\Validator\Constraints as Assert;
use ApiPlatform\Metadata\ApiProperty;



#[ORM\Entity(repositoryClass: SpaceRepository::class)]
#[ORM\Table(name: 'space', schema: 'divercity')]
#[ApiResource(
    normalizationContext: [
        'groups' => [
            self::GROUP_READ,
            MediaObject::READ,
            SpaceHighlight::GROUP_READ,
            'file_object:read',
        ],
    ],
    denormalizationContext: [
        'groups' => [self::GROUP_WRITE],
        'disable_type_enforcement' => true,
    ],
    operations: [
        new GetCollection(),
        new Get(),
        new Post(security: 'is_granted("ROLE_ADMIN")'), // création reste PDC uniquement
        new Put(security: "is_granted('".SpaceScopedVoter::MANAGE_SPACE."', object)"),
        new Patch(security: "is_granted('".SpaceScopedVoter::MANAGE_SPACE."', object)"),
        new Delete(security: 'is_granted("ROLE_ADMIN")'),
    ],
)]
class Space
{
    use TimestampableEntity;
    use BlameableEntity;
    use ValidateableEntity;
    use SluggableEntity;
    use LocalizableEntity;

    public const GROUP_READ = 'divercity_space:read';
    public const GROUP_WRITE = 'divercity_space:write';

    public function __construct()
    {
        $this->photos = new ArrayCollection();
        $this->blockedPeriods = new ArrayCollection();
        $this->bookings = new ArrayCollection();
        $this->admins = new ArrayCollection();
        $this->favorites = new ArrayCollection();
        $this->highlights = new ArrayCollection();
    }

    #[ORM\Id]
    #[ORM\Column(type: 'uuid', unique: true)]
    #[ORM\GeneratedValue(strategy: 'CUSTOM')]
    #[ORM\CustomIdGenerator('doctrine.uuid_generator')]
    #[Groups([self::GROUP_READ, Booking::GROUP_READ])]
    private ?Uuid $id = null;

    #[ORM\Column(length: 150)]
    #[Assert\NotBlank]
    #[Groups([self::GROUP_READ, self::GROUP_WRITE, Booking::GROUP_READ])]
    private ?string $name = null;

    /**
     * Contenu HTML riche (couleur, police, taille...) édité par l'admin,
     * affiché tel quel sur la page d'accueil.
     */
    #[ORM\Column(type: Types::TEXT, nullable: true)]
    #[Groups([self::GROUP_READ, self::GROUP_WRITE])]
    private ?string $description = null;

    #[ORM\Column]
    #[Assert\Positive]
    #[Groups([self::GROUP_READ, self::GROUP_WRITE])]
    private ?int $maxCapacity = null;

    #[ORM\Column(length: 50, nullable: true)]
    #[Groups([self::GROUP_READ, self::GROUP_WRITE])]
    private ?string $contact = null;

    #[ORM\Column(length: 150, nullable: true)]
    #[Assert\Email]
    #[Groups([self::GROUP_READ, self::GROUP_WRITE])]
    private ?string $email = null;

    #[ORM\Column(length: 500, nullable: true)]
    #[Groups([self::GROUP_READ, self::GROUP_WRITE])]
    private ?string $videoLink = null;

    /**
     * Valeurs séparées par ';'.
     */
    #[ORM\Column(type: Types::TEXT, nullable: true)]
    #[Groups([self::GROUP_READ, self::GROUP_WRITE])]
    private ?string $equipment = null;

    /**
     * @var Collection<int, MediaObject>
     */
    #[ORM\ManyToMany(targetEntity: MediaObject::class, cascade: ['remove'], orphanRemoval: true)]
    #[ORM\JoinTable(name: 'space_media_object', schema: 'divercity')]
    #[ORM\JoinColumn(name: 'space_id', referencedColumnName: 'id')]
    #[ORM\InverseJoinColumn(name: 'media_object_id', referencedColumnName: 'id')]
    #[ApiProperty(readableLink: true)]
    #[Groups([self::GROUP_READ, self::GROUP_WRITE])]
    private Collection $photos;

    /**
     * @var Collection<int, BlockedPeriod>
     */
    #[ORM\OneToMany(targetEntity: BlockedPeriod::class, mappedBy: 'space', orphanRemoval: true)]
    private Collection $blockedPeriods;

    /**
     * @var Collection<int, Booking>
     */
    #[ORM\OneToMany(targetEntity: Booking::class, mappedBy: 'space')]
    private Collection $bookings;

    /**
     * @var Collection<int, SpaceAdmin>
     */
    #[ORM\OneToMany(targetEntity: SpaceAdmin::class, mappedBy: 'space', orphanRemoval: true)]
    private Collection $admins;

    /**
     * @var Collection<int, EventActivityFavorite>
     */
    #[ORM\OneToMany(targetEntity: EventActivityFavorite::class, mappedBy: 'space', orphanRemoval: true)]
    private Collection $favorites;

    /**
     * @var Collection<int, SpaceHighlight>
     */
    #[ORM\OneToMany(targetEntity: SpaceHighlight::class, mappedBy: 'space', cascade: ['persist', 'remove'], orphanRemoval: true)]
    #[Groups([self::GROUP_READ])]
    private Collection $highlights;

    // --- Override traits groups (même pattern que Actor.php) ---

    #[Gedmo\Timestampable(on: 'create')]
    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    #[Groups([self::GROUP_READ])]
    protected ?\DateTimeInterface $createdAt;

    #[Gedmo\Timestampable(on: 'update')]
    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    #[Groups([self::GROUP_READ])]
    protected ?\DateTimeInterface $updatedAt;

    #[ORM\ManyToOne(targetEntity: User::class)]
    #[ORM\JoinColumn(nullable: true, onDelete: 'SET NULL', name: 'created_by')]
    #[Gedmo\Blameable(on: 'create')]
    #[Groups([self::GROUP_READ])]
    protected ?User $createdBy;

    #[ORM\ManyToOne(targetEntity: User::class)]
    #[ORM\JoinColumn(nullable: true, onDelete: 'SET NULL', name: 'updated_by')]
    #[Gedmo\Blameable(on: 'update')]
    protected ?User $updatedBy;

    #[ORM\Column]
    #[Groups([self::GROUP_READ])]
    private ?bool $isValidated = false;

    #[ORM\Column(length: 128, unique: true, nullable: true)]
    #[Gedmo\Slug(fields: ['name'])]
    #[Groups([self::GROUP_READ])]
    private ?string $slug;

    #[ORM\OneToOne(cascade: ['persist', 'remove'])]
    #[ORM\JoinColumn(name: 'geo_data_id', referencedColumnName: 'id')]
    #[Groups([self::GROUP_READ, self::GROUP_WRITE])]
    private ?GeoData $geoData = null;

    // --- Fin override ---

    public function getId(): ?Uuid
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

    public function setDescription(?string $description): static
    {
        $this->description = $description;

        return $this;
    }

    public function getMaxCapacity(): ?int
    {
        return $this->maxCapacity;
    }

    public function setMaxCapacity(int $maxCapacity): static
    {
        $this->maxCapacity = $maxCapacity;

        return $this;
    }

    public function getContact(): ?string
    {
        return $this->contact;
    }

    public function setContact(?string $contact): static
    {
        $this->contact = $contact;

        return $this;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(?string $email): static
    {
        $this->email = $email;

        return $this;
    }

    public function getVideoLink(): ?string
    {
        return $this->videoLink;
    }

    public function setVideoLink(?string $videoLink): static
    {
        $this->videoLink = $videoLink;

        return $this;
    }

    public function getEquipment(): ?string
    {
        return $this->equipment;
    }

    public function setEquipment(?string $equipment): static
    {
        $this->equipment = $equipment;

        return $this;
    }

    /**
     * @return Collection<int, MediaObject>
     */
    public function getPhotos(): Collection
    {
        return $this->photos;
    }

    public function addPhoto(MediaObject $photo): static
    {
        if (!$this->photos->contains($photo)) {
            $this->photos->add($photo);
        }

        return $this;
    }

    public function removePhoto(MediaObject $photo): static
    {
        $this->photos->removeElement($photo);

        return $this;
    }

    /**
     * @return Collection<int, BlockedPeriod>
     */
    public function getBlockedPeriods(): Collection
    {
        return $this->blockedPeriods;
    }

    public function addBlockedPeriod(BlockedPeriod $blockedPeriod): static
    {
        if (!$this->blockedPeriods->contains($blockedPeriod)) {
            $this->blockedPeriods->add($blockedPeriod);
            $blockedPeriod->setSpace($this);
        }

        return $this;
    }

    public function removeBlockedPeriod(BlockedPeriod $blockedPeriod): static
    {
        if ($this->blockedPeriods->removeElement($blockedPeriod)) {
            if ($blockedPeriod->getSpace() === $this) {
                $blockedPeriod->setSpace(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Booking>
     */
    public function getBookings(): Collection
    {
        return $this->bookings;
    }

    /**
     * @return Collection<int, SpaceAdmin>
     */
    public function getAdmins(): Collection
    {
        return $this->admins;
    }

    /**
     * @return Collection<int, EventActivityFavorite>
     */
    public function getFavorites(): Collection
    {
        return $this->favorites;
    }

    /**
     * @return Collection<int, SpaceHighlight>
     */
    public function getHighlights(): Collection
    {
        return $this->highlights;
    }

    public function addHighlight(SpaceHighlight $highlight): static
    {
        if (!$this->highlights->contains($highlight)) {
            $this->highlights->add($highlight);
            $highlight->setSpace($this);
        }

        return $this;
    }

    public function removeHighlight(SpaceHighlight $highlight): static
    {
        if ($this->highlights->removeElement($highlight)) {
            if ($highlight->getSpace() === $this) {
                $highlight->setSpace(null);
            }
        }

        return $this;
    }
}
