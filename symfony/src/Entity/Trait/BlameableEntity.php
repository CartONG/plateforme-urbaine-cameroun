<?php

namespace App\Entity\Trait;

use App\Entity\Actor;
use App\Entity\Project;
use App\Entity\User;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Symfony\Component\Serializer\Attribute\Groups;

trait BlameableEntity
{
    #[ORM\ManyToOne(targetEntity: User::class)]
    #[ORM\JoinColumn(nullable: true, onDelete: 'SET NULL', name: 'created_by')]
    #[Gedmo\Blameable(on: 'create')]
    #[Groups([Project::PROJECT_READ, Actor::ACTOR_READ_ITEM])]
    protected ?User $createdBy;

    #[ORM\ManyToOne(targetEntity: User::class)]
    #[ORM\JoinColumn(nullable: true, onDelete: 'SET NULL', name: 'updated_by')]
    #[Gedmo\Blameable(on: 'update')]
    protected ?User $updatedBy;

    public function setCreatedBy(User $createdBy): self
    {
        $this->createdBy = $createdBy;

        return $this;
    }

    public function getCreatedBy(): ?User
    {
        return $this->createdBy;
    }

    public function setUpdatedBy(User $updatedBy): self
    {
        $this->updatedBy = $updatedBy;

        return $this;
    }

    public function getUpdatedBy(): ?User
    {
        return $this->updatedBy;
    }
}
