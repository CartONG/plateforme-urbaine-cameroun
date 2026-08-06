<?php

namespace App\Entity\DiverCity;

use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\GetCollection;
use App\Repository\DiverCity\StatusRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Attribute\Groups;

#[ORM\Entity(repositoryClass: StatusRepository::class)]
#[ORM\Table(name: 'status', schema: 'divercity')]
#[ApiResource(
    operations: [
        new GetCollection(),
        new Get(),
    ],
    normalizationContext: ['groups' => [self::GROUP_READ]],
)]
class Status
{
    public const GROUP_READ = 'divercity_status:read';

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups([self::GROUP_READ, Booking::GROUP_READ])]
    private ?int $id = null;

    #[ORM\Column(length: 30, unique: true)]
    #[Groups([self::GROUP_READ, Booking::GROUP_READ])]
    private ?string $code = null;

    #[ORM\Column(length: 100)]
    #[Groups([self::GROUP_READ, Booking::GROUP_READ])]
    private ?string $label = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCode(): ?string
    {
        return $this->code;
    }

    public function setCode(string $code): static
    {
        $this->code = $code;

        return $this;
    }

    public function getLabel(): ?string
    {
        return $this->label;
    }

    public function setLabel(string $label): static
    {
        $this->label = $label;

        return $this;
    }
}
