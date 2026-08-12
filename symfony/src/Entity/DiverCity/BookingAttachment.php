<?php

namespace App\Entity\DiverCity;

use App\Entity\File\MediaObject;
use App\Repository\DiverCity\BookingAttachmentRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Symfony\Component\Serializer\Attribute\Groups;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Pièce jointe typée d'une réservation : ordre du jour, document ressource
 * (avec titre défini par le demandeur) ou autre document.
 */
#[ORM\Entity(repositoryClass: BookingAttachmentRepository::class)]
#[ORM\Table(name: 'booking_attachment', schema: 'divercity')]
class BookingAttachment
{
    public const TYPE_AGENDA = 'AGENDA';
    public const TYPE_RESOURCE_DOCUMENT = 'RESOURCE_DOCUMENT';
    public const TYPE_OTHER = 'OTHER';

    public const TYPES = [self::TYPE_AGENDA, self::TYPE_RESOURCE_DOCUMENT, self::TYPE_OTHER];

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups([Booking::GROUP_READ])]
    private ?int $id = null;

    #[ORM\ManyToOne(targetEntity: Booking::class, inversedBy: 'bookingAttachments')]
    #[ORM\JoinColumn(name: 'booking_id', referencedColumnName: 'id', nullable: false, onDelete: 'CASCADE')]
    private ?Booking $booking = null;

    #[ORM\ManyToOne(targetEntity: MediaObject::class)]
    #[ORM\JoinColumn(name: 'media_object_id', referencedColumnName: 'id', nullable: false, onDelete: 'CASCADE')]
    #[Assert\NotNull]
    #[Groups([Booking::GROUP_READ, Booking::GROUP_WRITE])]
    private ?MediaObject $mediaObject = null;

    #[ORM\Column(length: 30)]
    #[Assert\Choice(choices: self::TYPES)]
    #[Groups([Booking::GROUP_READ, Booking::GROUP_WRITE])]
    private ?string $type = null;

    #[Gedmo\Timestampable(on: 'create')]
    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    #[Groups([Booking::GROUP_READ])]
    private ?\DateTimeInterface $createdAt = null;

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

    public function getMediaObject(): ?MediaObject
    {
        return $this->mediaObject;
    }

    public function setMediaObject(?MediaObject $mediaObject): static
    {
        $this->mediaObject = $mediaObject;

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

    public function getCreatedAt(): ?\DateTimeInterface
    {
        return $this->createdAt;
    }
}
