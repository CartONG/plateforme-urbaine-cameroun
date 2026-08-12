<?php

namespace App\Entity\DiverCity;

use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\GetCollection;
use App\Entity\User\User;
use App\Repository\DiverCity\NotificationRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Symfony\Component\Serializer\Attribute\Groups;

#[ORM\Entity(repositoryClass: NotificationRepository::class)]
#[ORM\Table(name: 'notification', schema: 'divercity')]
#[ApiResource(
    operations: [
        new GetCollection(security: "is_granted('IS_AUTHENTICATED_FULLY')"),
        new Get(security: "is_granted('ROLE_ADMIN') or object.getUser() == user"),
    ],
    normalizationContext: ['groups' => [self::GROUP_READ]],
)]
class Notification
{
    public const GROUP_READ = 'divercity_notification:read';

    public const TYPE_SUBMISSION = 'SOUMISSION';
    public const TYPE_DECISION = 'DECISION';
    public const TYPE_CANCELLATION = 'ANNULATION';
    public const TYPE_WAITING_LIST = 'LISTE_ATTENTE';

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups([self::GROUP_READ])]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'notifications')]
    #[ORM\JoinColumn(name: 'booking_id', referencedColumnName: 'id', nullable: false, onDelete: 'CASCADE')]
    #[Groups([self::GROUP_READ])]
    private ?Booking $booking = null;

    #[ORM\ManyToOne]
    #[ORM\JoinColumn(name: 'user_id', referencedColumnName: 'id')]
    #[Groups([self::GROUP_READ])]
    private ?User $user = null;

    #[ORM\Column(length: 50)]
    #[Groups([self::GROUP_READ])]
    private ?string $type = null;

    #[ORM\Column(type: 'datetime')]
    #[Groups([self::GROUP_READ])]
    private ?\DateTimeInterface $sentAt = null;

    #[ORM\Column(type: 'text', nullable: true)]
    #[Groups([self::GROUP_READ])]
    private ?string $content = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getBooking(): ?Booking
    {
        return $this->booking;
    }

    public function setBooking(?Booking $booking): static
    {
        $this->booking = $booking;

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

    public function getType(): ?string
    {
        return $this->type;
    }

    public function setType(string $type): static
    {
        $this->type = $type;

        return $this;
    }

    public function getSentAt(): ?\DateTimeInterface
    {
        return $this->sentAt;
    }

    public function setSentAt(?\DateTimeInterface $sentAt): static
    {
        $this->sentAt = $sentAt;

        return $this;
    }

    public function getContent(): ?string
    {
        return $this->content;
    }

    public function setContent(?string $content): static
    {
        $this->content = $content;

        return $this;
    }
}