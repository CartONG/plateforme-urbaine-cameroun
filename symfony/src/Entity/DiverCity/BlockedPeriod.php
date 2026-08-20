<?php

namespace App\Entity\DiverCity;

use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Delete;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\GetCollection;
use ApiPlatform\Metadata\Post;
use App\Entity\User\User;
use App\Repository\DiverCity\BlockedPeriodRepository;
use App\Security\Voter\DiverCity\SpaceScopedVoter;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Symfony\Component\Serializer\Attribute\Groups;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: BlockedPeriodRepository::class)]
#[ORM\Table(name: 'blocked_period', schema: 'divercity')]
#[ApiResource(
    operations: [
        new GetCollection(),
        new Get(),
        new Post(security: "is_granted('".SpaceScopedVoter::MANAGE_SPACE."', object)"),
        new Delete(security: "is_granted('".SpaceScopedVoter::MANAGE_SPACE."', object)"),
    ],
    normalizationContext: ['groups' => [self::GROUP_READ]],
    denormalizationContext: ['groups' => [self::GROUP_WRITE]],
)]
class BlockedPeriod
{
    public const GROUP_READ = 'divercity_blocked_period:read';
    public const GROUP_WRITE = 'divercity_blocked_period:write';

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups([self::GROUP_READ])]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'blockedPeriods')]
    #[ORM\JoinColumn(name: 'space_id', referencedColumnName: 'id', nullable: false, onDelete: 'CASCADE')]
    #[Assert\NotNull]
    #[Groups([self::GROUP_READ, self::GROUP_WRITE])]
    private ?Space $space = null;

    #[ORM\ManyToOne(targetEntity: User::class)]
    #[ORM\JoinColumn(name: 'created_by', referencedColumnName: 'id', nullable: true, onDelete: 'SET NULL')]
    #[Gedmo\Blameable(on: 'create')]
    #[Groups([self::GROUP_READ])]
    private ?User $createdBy = null;

    #[ORM\Column(type: 'date')]
    #[Assert\NotNull]
    #[Groups([self::GROUP_READ, self::GROUP_WRITE])]
    private ?\DateTimeInterface $date = null;

    #[ORM\Column(type: 'time')]
    #[Assert\NotNull]
    #[Groups([self::GROUP_READ, self::GROUP_WRITE])]
    private ?\DateTimeInterface $startTime = null;

    #[ORM\Column(type: 'time')]
    #[Assert\NotNull]
    #[Groups([self::GROUP_READ, self::GROUP_WRITE])]
    private ?\DateTimeInterface $endTime = null;

    #[ORM\Column(length: 255, nullable: true)]
    #[Groups([self::GROUP_READ, self::GROUP_WRITE])]
    private ?string $reason = null;

    #[ORM\Column(options: ['default' => false])]
    #[Groups([self::GROUP_READ, self::GROUP_WRITE])]
    private bool $isUnblocked = false;

    #[Gedmo\Timestampable(on: 'create')]
    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    #[Groups([self::GROUP_READ])]
    private ?\DateTimeInterface $createdAt = null;

    public function getId(): ?int
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

    public function getCreatedBy(): ?User
    {
        return $this->createdBy;
    }

    public function setCreatedBy(?User $createdBy): static
    {
        $this->createdBy = $createdBy;

        return $this;
    }

    public function getDate(): ?\DateTimeInterface
    {
        return $this->date;
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

    public function setStartTime(\DateTimeInterface $startTime): static
    {
        $this->startTime = $startTime;

        return $this;
    }

    public function getEndTime(): ?\DateTimeInterface
    {
        return $this->endTime;
    }

    public function setEndTime(\DateTimeInterface $endTime): static
    {
        $this->endTime = $endTime;

        return $this;
    }

    public function getReason(): ?string
    {
        return $this->reason;
    }

    public function setReason(?string $reason): static
    {
        $this->reason = $reason;

        return $this;
    }

    public function isUnblocked(): bool
    {
        return $this->isUnblocked;
    }

    public function setIsUnblocked(bool $isUnblocked): static
    {
        $this->isUnblocked = $isUnblocked;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeInterface
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTimeInterface $createdAt): static
    {
        $this->createdAt = $createdAt;

        return $this;
    }
}
