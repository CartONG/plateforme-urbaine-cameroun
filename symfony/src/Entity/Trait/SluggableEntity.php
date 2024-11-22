<?php

namespace App\Entity\Trait;

use App\Entity\Actor;
use App\Entity\Project;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Symfony\Component\Serializer\Attribute\Groups;

trait SluggableEntity 
{
    #[ORM\Column(length: 128, unique: true, nullable: true)]
    #[Gedmo\Slug(fields: ['name'])]
    #[Groups([Project::PROJECT_READ_ALL, Project::PROJECT_READ, Actor::ACTOR_READ_COLLECTION, Actor::ACTOR_READ_ITEM])]
    private ?string $slug;

    public function getSlug(): ?string
    {
        return $this->slug;
    }

    public function setSlug($slug): static
    {
        $this->slug = $slug;
        return $this;
    }
}
