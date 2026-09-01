<?php

namespace App\Entity\DiverCity;

use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\GetCollection;
use ApiPlatform\Metadata\Patch;
use ApiPlatform\Metadata\Post;
use App\Entity\Resource;
use App\Entity\User\User;
use App\Repository\DiverCity\BookingRepository;
use App\Security\Voter\DiverCity\SpaceScopedVoter;
use App\Services\State\Processor\DiverCity\BookingCancellationProcessor;
use App\Services\State\Processor\DiverCity\BookingDecisionProcessor;
use App\Services\State\Processor\DiverCity\BookingEditProcessor;
use App\Services\State\Processor\DiverCity\BookingInformationSourceProcessor;
use App\Services\State\Processor\DiverCity\BookingResourcesProcessor;
use App\Services\State\Processor\DiverCity\BookingSubmissionProcessor;
use App\Services\State\Provider\DiverCity\ManagedBookingsProvider;
use App\Services\State\Provider\DiverCity\MyBookingsProvider;
use App\Services\State\Provider\DiverCity\PublicBookingsProvider;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Symfony\Component\Serializer\Attribute\Groups;
use Symfony\Component\Serializer\Attribute\SerializedName;
use Symfony\Component\Uid\Uuid;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\Context\ExecutionContextInterface;

#[ORM\Entity(repositoryClass: BookingRepository::class)]
#[ORM\Table(name: 'booking', schema: 'divercity')]
#[Assert\Callback('validateRequiredAttachments')]
#[ApiResource(
    operations: [
        new GetCollection(security: "is_granted('ROLE_ADMIN')"),
        new GetCollection(
            uriTemplate: '/divercity/bookings/managed',
            security: "is_granted('IS_AUTHENTICATED_FULLY')",
            provider: ManagedBookingsProvider::class,
        ),
        new GetCollection(
            uriTemplate: '/divercity/bookings/mine',
            security: "is_granted('IS_AUTHENTICATED_FULLY')",
            provider: MyBookingsProvider::class,
            paginationItemsPerPage: 20,
        ),
        new GetCollection(
            uriTemplate: '/divercity/bookings/public',
            normalizationContext: ['groups' => [self::GROUP_PUBLIC]],
            provider: PublicBookingsProvider::class,
        ),
        new Get(security: "is_granted('".SpaceScopedVoter::MANAGE_SPACE."', object) or object.getUser() == user"),
        new Post(
            security: "is_granted('IS_AUTHENTICATED_FULLY')",
            processor: BookingSubmissionProcessor::class
        ),
        new Patch(
            uriTemplate: '/divercity/bookings/{id}/information-source',
            security: 'object.getUser() == user',
            processor: BookingInformationSourceProcessor::class
        ),
        new Patch(
            security: "is_granted('".SpaceScopedVoter::MANAGE_SPACE."', object)",
            denormalizationContext: ['groups' => [self::GROUP_ADMIN]],
            processor: BookingDecisionProcessor::class
        ),
        new Patch(
            uriTemplate: '/divercity/bookings/{id}/cancel',
            security: "is_granted('".SpaceScopedVoter::MANAGE_SPACE."', object) or object.getUser() == user",
            // denormalizationContext: ['groups' => [self::GROUP_ADMIN]],
            denormalizationContext: ['groups' => [self::GROUP_CANCEL]],
            processor: BookingCancellationProcessor::class
        ), // annulation par le demandeur ou un admin
        new Patch(
            uriTemplate: '/divercity/bookings/{id}/edit',
            security: 'object.getUser() == user',
            denormalizationContext: ['groups' => [self::GROUP_EDIT]],
            processor: BookingEditProcessor::class
        ),
        new Patch(
            uriTemplate: '/divercity/bookings/{id}/resources',
            security: "is_granted('".SpaceScopedVoter::MANAGE_SPACE."', object)",
            denormalizationContext: ['groups' => [self::GROUP_RESOURCES]],
            processor: BookingResourcesProcessor::class
        ),
    ],
    normalizationContext: ['groups' => [self::GROUP_READ]],
    denormalizationContext: ['groups' => [self::GROUP_WRITE]],
)]
class Booking
{
    public const GROUP_READ = 'divercity_booking:read';
    public const GROUP_WRITE = 'divercity_booking:write';
    public const GROUP_ADMIN = 'divercity_booking:admin'; // décision (statut, motifs, traitant)
    public const GROUP_PUBLIC = 'divercity_booking:public';
    public const GROUP_CANCEL = 'divercity_booking:cancel';
    public const GROUP_EDIT = 'divercity_booking:edit';
    public const GROUP_RESOURCES = 'divercity_booking:resources';

    #[ORM\Id]
    #[ORM\Column(type: 'uuid', unique: true)]
    #[ORM\GeneratedValue(strategy: 'CUSTOM')]
    #[ORM\CustomIdGenerator('doctrine.uuid_generator')]
    #[Groups([self::GROUP_READ, self::GROUP_PUBLIC])]
    private ?Uuid $id = null;

    #[ORM\ManyToOne(inversedBy: 'bookings')]
    #[ORM\JoinColumn(name: 'space_id', referencedColumnName: 'id', nullable: false)]
    #[Assert\NotNull]
    #[Groups([self::GROUP_READ, self::GROUP_WRITE])]
    private ?Space $space = null;

    #[ORM\ManyToOne]
    #[ORM\JoinColumn(name: 'user_id', referencedColumnName: 'id', nullable: false)]
    #[Groups([self::GROUP_READ])]
    private ?User $user = null;

    #[ORM\ManyToOne]
    #[ORM\JoinColumn(name: 'processing_user_id', referencedColumnName: 'id')]
    #[Groups([self::GROUP_READ, self::GROUP_ADMIN])]
    private ?User $processingUser = null;

    #[ORM\ManyToOne]
    #[ORM\JoinColumn(name: 'status_id', referencedColumnName: 'id', nullable: false)]
    #[Groups([self::GROUP_READ, self::GROUP_ADMIN])]
    private ?Status $status = null;

    #[ORM\ManyToOne]
    #[ORM\JoinColumn(name: 'event_activity_type_id', referencedColumnName: 'id')]
    #[Groups([self::GROUP_READ, self::GROUP_WRITE, self::GROUP_PUBLIC, self::GROUP_EDIT])]
    private ?EventActivityType $eventActivityType = null;

    #[ORM\ManyToOne]
    #[ORM\JoinColumn(name: 'information_source_id', referencedColumnName: 'id')]
    #[Groups([self::GROUP_READ, self::GROUP_WRITE])]
    private ?InformationSource $informationSource = null;

    #[ORM\Column(length: 200)]
    #[Assert\NotBlank]
    #[Groups([self::GROUP_READ, self::GROUP_WRITE, self::GROUP_PUBLIC, self::GROUP_EDIT])]
    private ?string $title = null;

    #[ORM\Column(length: 100)]
    #[Assert\NotBlank]
    #[Groups([self::GROUP_READ, self::GROUP_WRITE, self::GROUP_EDIT])]
    private ?string $lastName = null;

    #[ORM\Column(length: 100)]
    #[Assert\NotBlank]
    #[Groups([self::GROUP_READ, self::GROUP_WRITE, self::GROUP_EDIT])]
    private ?string $firstName = null;

    #[ORM\Column(length: 150, nullable: true)]
    #[Groups([self::GROUP_READ, self::GROUP_WRITE, self::GROUP_PUBLIC, self::GROUP_EDIT])]
    private ?string $organization = null;

    #[ORM\Column(length: 150)]
    #[Assert\NotBlank]
    #[Groups([self::GROUP_READ, self::GROUP_WRITE, self::GROUP_EDIT])]
    private ?string $role = null;

    #[ORM\Column(length: 150)]
    #[Assert\NotBlank]
    #[Assert\Email]
    #[Groups([self::GROUP_READ, self::GROUP_WRITE, self::GROUP_EDIT])]
    private ?string $email = null;

    #[ORM\Column(length: 30)]
    #[Assert\NotBlank]
    #[Groups([self::GROUP_READ, self::GROUP_WRITE, self::GROUP_EDIT])]
    private ?string $phone = null;

    #[ORM\Column(type: 'text')]
    #[Assert\NotBlank]
    #[Groups([self::GROUP_READ, self::GROUP_WRITE, self::GROUP_EDIT])]
    private ?string $bookingPurpose = null;

    #[ORM\Column(type: 'date')]
    #[Assert\NotNull]
    #[Groups([self::GROUP_WRITE, self::GROUP_EDIT])]
    private ?\DateTimeInterface $date = null;

    #[ORM\Column(type: 'time')]
    #[Assert\NotNull]
    #[Groups([self::GROUP_WRITE, self::GROUP_EDIT])]
    private ?\DateTimeInterface $startTime = null;

    #[ORM\Column(type: 'time')]
    #[Assert\NotNull]
    #[Groups([self::GROUP_WRITE, self::GROUP_EDIT])]
    private ?\DateTimeInterface $endTime = null;

    #[ORM\Column]
    #[Assert\Positive]
    #[Groups([self::GROUP_READ, self::GROUP_WRITE, self::GROUP_PUBLIC, self::GROUP_EDIT])]
    private ?int $participantCount = null;

    #[ORM\Column(type: 'text', nullable: true)]
    #[Groups([self::GROUP_READ, self::GROUP_WRITE, self::GROUP_EDIT])]
    private ?string $additionalInformation = null;

    #[ORM\Column(type: 'text', nullable: true)]
    #[Groups([self::GROUP_READ, self::GROUP_ADMIN])]
    private ?string $refusalReason = null;

    #[ORM\Column(type: 'text', nullable: true)]
    #[Groups([self::GROUP_READ, self::GROUP_ADMIN, self::GROUP_CANCEL])]
    private ?string $cancellationReason = null;

    #[Gedmo\Timestampable(on: 'create')]
    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    #[Groups([self::GROUP_READ])]
    private ?\DateTimeInterface $submittedAt = null;

    #[ORM\Column(type: 'datetime', nullable: true)]
    #[Groups([self::GROUP_READ, self::GROUP_ADMIN])]
    private ?\DateTimeInterface $processedAt = null;

    /**
     * @var Collection<int, BookingAttachment>
     */
    #[ORM\OneToMany(targetEntity: BookingAttachment::class, mappedBy: 'booking', cascade: ['persist', 'remove'], orphanRemoval: true)]
    #[Groups([self::GROUP_READ, self::GROUP_WRITE, self::GROUP_EDIT])]
    private Collection $bookingAttachments;

    /**
     * @var Collection<int, resource>
     */
    #[ORM\ManyToMany(targetEntity: Resource::class)]
    #[ORM\JoinTable(name: 'booking_resource', schema: 'divercity')]
    #[ORM\JoinColumn(name: 'booking_id', referencedColumnName: 'id')]
    #[ORM\InverseJoinColumn(name: 'resource_id', referencedColumnName: 'id')]
    #[Groups([self::GROUP_READ, self::GROUP_WRITE, self::GROUP_RESOURCES])]
    private Collection $resources;

    /**
     * @var Collection<int, Notification>
     */
    #[ORM\OneToMany(targetEntity: Notification::class, mappedBy: 'booking', orphanRemoval: true)]
    private Collection $notifications;

    public function __construct()
    {
        $this->bookingAttachments = new ArrayCollection();
        $this->resources = new ArrayCollection();
        $this->notifications = new ArrayCollection();
    }

    public function getId(): ?Uuid
    {
        return $this->id;
    }

    public function getSpace(): ?Space
    {
        return $this->space;
    }

    public function setSpace(?Space $space): static
    {
        $this->space = $space;

        return $this;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): static
    {
        $this->user = $user;

        return $this;
    }

    public function getProcessingUser(): ?User
    {
        return $this->processingUser;
    }

    public function setProcessingUser(?User $processingUser): static
    {
        $this->processingUser = $processingUser;

        return $this;
    }

    public function getStatus(): ?Status
    {
        return $this->status;
    }

    public function setStatus(?Status $status): static
    {
        $this->status = $status;

        return $this;
    }

    public function getEventActivityType(): ?EventActivityType
    {
        return $this->eventActivityType;
    }

    public function setEventActivityType(?EventActivityType $eventActivityType): static
    {
        $this->eventActivityType = $eventActivityType;

        return $this;
    }

    public function getInformationSource(): ?InformationSource
    {
        return $this->informationSource;
    }

    public function setInformationSource(?InformationSource $informationSource): static
    {
        $this->informationSource = $informationSource;

        return $this;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(?string $title): static
    {
        $this->title = $title;

        return $this;
    }

    public function getLastName(): ?string
    {
        return $this->lastName;
    }

    public function setLastName(string $lastName): static
    {
        $this->lastName = $lastName;

        return $this;
    }

    public function getFirstName(): ?string
    {
        return $this->firstName;
    }

    public function setFirstName(string $firstName): static
    {
        $this->firstName = $firstName;

        return $this;
    }

    public function getOrganization(): ?string
    {
        return $this->organization;
    }

    public function setOrganization(?string $organization): static
    {
        $this->organization = $organization;

        return $this;
    }

    public function getRole(): ?string
    {
        return $this->role;
    }

    public function setRole(string $role): static
    {
        $this->role = $role;

        return $this;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): static
    {
        $this->email = $email;

        return $this;
    }

    public function getPhone(): ?string
    {
        return $this->phone;
    }

    public function setPhone(string $phone): static
    {
        $this->phone = $phone;

        return $this;
    }

    public function getBookingPurpose(): ?string
    {
        return $this->bookingPurpose;
    }

    public function setBookingPurpose(string $bookingPurpose): static
    {
        $this->bookingPurpose = $bookingPurpose;

        return $this;
    }

    public function getDate(): ?\DateTimeInterface
    {
        return $this->date;
    }

    #[Groups([self::GROUP_READ, self::GROUP_PUBLIC])]
    #[SerializedName('date')]
    public function getDateFormat(): ?string
    {
        return $this->date?->format('Y-m-d');
    }

    public function setDate(\DateTimeInterface $date): static
    {
        $this->date = $date;

        return $this;
    }

    public function getStartTime(): ?\DateTimeInterface
    {
        return $this->startTime;
    }

    #[Groups([self::GROUP_READ, self::GROUP_PUBLIC])]
    #[SerializedName('startTime')]
    public function getStartTimeFormat(): ?string
    {
        return $this->startTime?->format('H:i');
    }

    public function setStartTime(\DateTimeInterface $startTime): static
    {
        $this->startTime = $startTime;

        return $this;
    }

    public function getEndTime(): ?\DateTimeInterface
    {
        return $this->endTime;
    }

    #[Groups([self::GROUP_READ, self::GROUP_PUBLIC])]
    #[SerializedName('endTime')]
    public function getEndTimeFormat(): ?string
    {
        return $this->endTime?->format('H:i');
    }

    public function setEndTime(\DateTimeInterface $endTime): static
    {
        $this->endTime = $endTime;

        return $this;
    }

    public function getParticipantCount(): ?int
    {
        return $this->participantCount;
    }

    public function setParticipantCount(int $participantCount): static
    {
        $this->participantCount = $participantCount;

        return $this;
    }

    public function getAdditionalInformation(): ?string
    {
        return $this->additionalInformation;
    }

    public function setAdditionalInformation(?string $additionalInformation): static
    {
        $this->additionalInformation = $additionalInformation;

        return $this;
    }

    public function getRefusalReason(): ?string
    {
        return $this->refusalReason;
    }

    public function setRefusalReason(?string $refusalReason): static
    {
        $this->refusalReason = $refusalReason;

        return $this;
    }

    public function getCancellationReason(): ?string
    {
        return $this->cancellationReason;
    }

    public function setCancellationReason(?string $cancellationReason): static
    {
        $this->cancellationReason = $cancellationReason;

        return $this;
    }

    public function getSubmittedAt(): ?\DateTimeInterface
    {
        return $this->submittedAt;
    }

    public function getProcessedAt(): ?\DateTimeInterface
    {
        return $this->processedAt;
    }

    public function setProcessedAt(?\DateTimeInterface $processedAt): static
    {
        $this->processedAt = $processedAt;

        return $this;
    }

    /**
     * @return Collection<int, BookingAttachment>
     */
    public function getBookingAttachments(): Collection
    {
        return $this->bookingAttachments;
    }

    public function addBookingAttachment(BookingAttachment $bookingAttachment): static
    {
        if (!$this->bookingAttachments->contains($bookingAttachment)) {
            $this->bookingAttachments->add($bookingAttachment);
            $bookingAttachment->setBooking($this);
        }

        return $this;
    }

    public function removeBookingAttachment(BookingAttachment $bookingAttachment): static
    {
        if ($this->bookingAttachments->removeElement($bookingAttachment)) {
            if ($bookingAttachment->getBooking() === $this) {
                $bookingAttachment->setBooking(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, BookingAttachment>
     */
    public function getAttachmentsByType(string $type): Collection
    {
        return $this->bookingAttachments->filter(
            fn (BookingAttachment $attachment) => $attachment->getType() === $type
        );
    }

    /**
     * Vérifie qu'au moins un ordre du jour et un document ressource ont été
     * fournis avant toute soumission de réservation.
     */
    public function validateRequiredAttachments(ExecutionContextInterface $context): void
    {
        $hasAgenda = false;
        $hasResourceDocument = false;

        foreach ($this->bookingAttachments as $attachment) {
            if (BookingAttachment::TYPE_AGENDA === $attachment->getType()) {
                $hasAgenda = true;
            }
            if (BookingAttachment::TYPE_RESOURCE_DOCUMENT === $attachment->getType()) {
                $hasResourceDocument = true;
            }
        }

        if (!$hasAgenda) {
            $context->buildViolation('Un ordre du jour est obligatoire.')
                ->atPath('bookingAttachments')
                ->addViolation();
        }

        if (!$hasResourceDocument) {
            $context->buildViolation('Un document ressource est obligatoire.')
                ->atPath('bookingAttachments')
                ->addViolation();
        }
    }

    /**
     * @return Collection<int, resource>
     */
    public function getResources(): Collection
    {
        return $this->resources;
    }

    public function addResource(Resource $resource): static
    {
        if (!$this->resources->contains($resource)) {
            $this->resources->add($resource);
        }

        return $this;
    }

    public function removeResource(Resource $resource): static
    {
        $this->resources->removeElement($resource);

        return $this;
    }

    /**
     * @return Collection<int, Notification>
     */
    public function getNotifications(): Collection
    {
        return $this->notifications;
    }
}
