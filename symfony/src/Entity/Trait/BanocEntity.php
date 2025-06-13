<?php

namespace App\Entity\Trait;

use App\Entity\Actor;
use App\Entity\Project;
use App\Entity\Resource;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Attribute\Groups;

trait BanocEntity
{
    #[ORM\Column(length: 128, unique: true, nullable: true)]
    #[Groups([Actor::ACTOR_READ_COLLECTION, Actor::ACTOR_READ_ITEM, Actor::ACTOR_WRITE, Project::GET_FULL, Project::GET_PARTIAL, Project::WRITE, Resource::GET_FULL, Resource::WRITE])]
    private ?string $banoc = null;

    #[ORM\Column(length: 128, unique: true, nullable: true)]
    #[Groups([Actor::ACTOR_READ_COLLECTION, Actor::ACTOR_READ_ITEM, Actor::ACTOR_WRITE, Project::GET_FULL, Project::GET_PARTIAL, Project::WRITE, Resource::GET_FULL, Resource::WRITE])]
    private ?string $banocUrl = null;

    public function getBanoc(): ?string
    {
        return $this->banoc;
    }

    public function setBanoc($banoc): static
    {
        $this->banoc = $banoc;

        return $this;
    }

    public function getBanocUrl(): ?string
    {
        return $this->banocUrl;
    }

    public function setBanocUrl($banocUrl): static
    {
        $this->banocUrl = $banocUrl;

        return $this;
    }
}
