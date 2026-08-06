<?php

namespace App\Entity\DiverCity;

use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\GetCollection;
use App\Repository\DiverCity\InformationSourceRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Attribute\Groups;

#[ORM\Entity(repositoryClass: InformationSourceRepository::class)]
#[ORM\Table(name: 'information_source', schema: 'divercity')]
#[ApiResource(
    operations: [
        new GetCollection(),
        new Get(),
    ],
    normalizationContext: ['groups' => [self::GROUP_READ]],
)]
class InformationSource
{
    public const GROUP_READ = 'divercity_information_source:read';

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups([self::GROUP_READ, Booking::GROUP_READ])]
    private ?int $id = null;

    #[ORM\Column(length: 150, unique: true)]
    #[Groups([self::GROUP_READ, Booking::GROUP_READ])]
    private ?string $label = null;

    public function getId(): ?int
    {
        return $this->id;
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