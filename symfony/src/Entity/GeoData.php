<?php

namespace App\Entity;

use App\Repository\GeoDataRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Attribute\Groups;

#[ORM\Entity(repositoryClass: GeoDataRepository::class)]
class GeoData
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(type: 'bigint')]
    #[Groups([Project::PROJECT_READ])]
    private ?int $osmId = null;

    #[ORM\Column]
    #[Groups([Project::PROJECT_READ])]
    private ?string $osmType = 'node';

    #[ORM\Column(length: 255)]
    #[Groups([PROJECT::PROJECT_READ, PROJECT::PROJECT_READ_ALL, Actor::ACTOR_READ_ITEM])]
    private ?string $name = null;

    #[ORM\Column(nullable: true)]
    private ?float $latitude = null;

    #[ORM\Column(nullable: true)]
    private ?float $longitude = null;

    #[ORM\Column(nullable: true)]
    private ?array $bounds = null;

    #[ORM\Column(nullable: true)]
    private ?array $address = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getOsmId(): ?int
    {
        return $this->osmId;
    }

    public function setOsmId(int $osmId): static
    {
        $this->osmId = $osmId;

        return $this;
    }

    public function getOsmType(): ?string
    {
        return $this->osmType;
    }

    public function setOsmType(string $osmType): static
    {
        $this->osmType = $osmType;

        return $this;
    }

    public function getLatitude(): ?float
    {
        return $this->latitude;
    }

    public function setLatitude(?float $latitude): static
    {
        $this->latitude = $latitude;

        return $this;
    }

    public function getLongitude(): ?float
    {
        return $this->longitude;
    }

    public function setLongitude(?float $longitude): static
    {
        $this->longitude = $longitude;

        return $this;
    }

    public function getBounds(): ?array
    {
        return $this->bounds;
    }

    public function setBounds(?array $bounds): static
    {
        $this->bounds = $bounds;

        return $this;
    }

    public function getAddress(): ?array
    {
        return $this->address;
    }

    public function setAddress(?array $address): static
    {
        $this->address = $address;

        return $this;
    }

    #[Groups([Project::PROJECT_READ_ALL])]
    public function getCoords(): ?array
    {
        return [
            'lat' => $this->latitude,
            'lng' => $this->longitude,
        ];
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
}
