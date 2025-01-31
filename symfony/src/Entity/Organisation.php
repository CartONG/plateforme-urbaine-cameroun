<?php

namespace App\Entity;

use ApiPlatform\Doctrine\Orm\Filter\BooleanFilter;
use ApiPlatform\Metadata\ApiFilter;
use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\GetCollection;
use App\Repository\OrganisationRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Attribute\Groups;

#[ORM\Entity(repositoryClass: OrganisationRepository::class)]
#[ApiResource(
    paginationEnabled: false,
    operations: [
        new GetCollection(normalizationContext: ['groups' => [self::ORGANISATION_READ_ALL]]),
    ]
)]
#[ApiFilter(BooleanFilter::class, properties: ['donor', 'contracting'])]
class Organisation
{
    public const ORGANISATION_READ_ALL = 'organisation:read:all';

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups([self::ORGANISATION_READ_ALL, Project::GET_FULL, Project::GET_PARTIAL])]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Groups([self::ORGANISATION_READ_ALL, Project::GET_FULL, Project::GET_PARTIAL])]
    private ?string $name = null;

    #[ORM\Column(length: 255)]
    #[Groups([self::ORGANISATION_READ_ALL, Project::GET_FULL, Project::GET_PARTIAL])]
    private ?string $acronym = null;

    #[ORM\OneToOne(cascade: ['persist', 'remove'])]
    private ?Actor $actor = null;

    #[ORM\Column(type: 'boolean', options: ['default' => false])]
    private ?bool $contracting = false;

    #[ORM\Column(type: 'boolean', options: ['default' => false])]
    private ?bool $donor = false;

    public function getId(): ?int
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

    public function getAcronym(): ?string
    {
        return $this->acronym;
    }

    public function setAcronym(string $acronym): static
    {
        $this->acronym = strtoupper($acronym);

        return $this;
    }

    public function getActor(): ?Actor
    {
        return $this->actor;
    }

    public function setActor(?Actor $actor): static
    {
        $this->actor = $actor;

        return $this;
    }

    public function isContracting(): ?bool
    {
        return $this->contracting;
    }

    public function setContracting(bool $contracting): static
    {
        $this->contracting = $contracting;

        return $this;
    }

    public function isDonor(): ?bool
    {
        return $this->donor;
    }

    public function setDonor(bool $donor): static
    {
        $this->donor = $donor;

        return $this;
    }
}
