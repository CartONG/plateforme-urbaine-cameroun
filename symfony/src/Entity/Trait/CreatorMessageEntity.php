<?php

namespace App\Entity\Trait;

use App\Entity\Actor;
use App\Entity\Project;
use App\Entity\Resource;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Attribute\Groups;
use Symfony\Component\Validator\Constraints as Assert;

trait CreatorMessageEntity
{
    #[ORM\Column(type: Types::TEXT, nullable: true)]
    #[Groups([Actor::ACTOR_READ_ITEM, Actor::ACTOR_WRITE, Project::GET_FULL, Project::WRITE, Resource::GET_FULL, Resource::WRITE])]
    #[Assert\Length(max: 1000)]
    private ?string $creatorMessage = null;

    public function getCreatorMessage(): ?string
    {
        return $this->creatorMessage;
    }

    public function setCreatorMessage(?string $creatorMessage): static
    {
        $this->creatorMessage = $creatorMessage;

        return $this;
    }
}
