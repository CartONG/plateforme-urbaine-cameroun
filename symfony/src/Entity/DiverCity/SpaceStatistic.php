<?php

namespace App\Entity\DiverCity;

use App\Repository\DiverCity\SpaceStatisticRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Symfony\Component\Serializer\Attribute\Groups;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Statistique manuelle du tiers-lieu (nom/valeur), saisie par l'admin pour
 * un SpaceHighlight (espace + année) donné. Sans lien avec les statistiques
 * générées automatiquement plus tard par le module.
 */
#[ORM\Entity(repositoryClass: SpaceStatisticRepository::class)]
#[ORM\Table(name: 'space_statistic', schema: 'divercity')]
class SpaceStatistic
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups([SpaceHighlight::GROUP_READ])]
    private ?int $id = null;

    #[ORM\ManyToOne(targetEntity: SpaceHighlight::class, inversedBy: 'statistics')]
    #[ORM\JoinColumn(name: 'space_highlight_id', referencedColumnName: 'id', nullable: false, onDelete: 'CASCADE')]
    private ?SpaceHighlight $spaceHighlight = null;

    #[ORM\Column(length: 150)]
    #[Assert\NotBlank]
    #[Groups([SpaceHighlight::GROUP_READ, SpaceHighlight::GROUP_WRITE, Space::GROUP_READ])]
    private ?string $label = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank]
    #[Groups([SpaceHighlight::GROUP_READ, SpaceHighlight::GROUP_WRITE, Space::GROUP_READ])]
    private ?string $value = null;

    /**
     * Ordre d'affichage sur la page d'accueil.
     */
    #[ORM\Column(options: ['default' => 0])]
    #[Groups([SpaceHighlight::GROUP_READ, SpaceHighlight::GROUP_WRITE, Space::GROUP_READ])]
    private int $position = 0;

    #[Gedmo\Timestampable(on: 'create')]
    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    #[Groups([SpaceHighlight::GROUP_READ])]
    private ?\DateTimeInterface $createdAt = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getSpaceHighlight(): ?SpaceHighlight
    {
        return $this->spaceHighlight;
    }

    public function setSpaceHighlight(?SpaceHighlight $spaceHighlight): static
    {
        $this->spaceHighlight = $spaceHighlight;

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

    public function getValue(): ?string
    {
        return $this->value;
    }

    public function setValue(string $value): static
    {
        $this->value = $value;

        return $this;
    }

    public function getPosition(): int
    {
        return $this->position;
    }

    public function setPosition(int $position): static
    {
        $this->position = $position;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeInterface
    {
        return $this->createdAt;
    }
}