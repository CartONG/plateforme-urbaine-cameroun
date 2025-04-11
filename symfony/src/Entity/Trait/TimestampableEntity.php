<?php

namespace App\Entity\Trait;

use App\Entity\Actor;
use App\Entity\AppContentComment;
use App\Entity\Atlas;
use App\Entity\Project;
use App\Entity\QgisMap;
use App\Entity\Resource;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Symfony\Component\Serializer\Attribute\Groups;

trait TimestampableEntity
{
    #[Gedmo\Timestampable(on: 'create')]
    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    #[Groups([Project::GET_FULL, Project::GET_PARTIAL, Actor::ACTOR_READ_ITEM, Actor::ACTOR_READ_COLLECTION, Resource::GET_FULL, Atlas::GET_FULL, QgisMap::GET_FULL, AppContentComment::COMMENT_READ])]
    protected ?\DateTimeInterface $createdAt;

    #[Gedmo\Timestampable(on: 'update')]
    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    #[Groups([Project::GET_FULL, Project::GET_PARTIAL, Actor::ACTOR_READ_ITEM, Actor::ACTOR_READ_COLLECTION, Resource::GET_FULL, Atlas::GET_FULL, QgisMap::GET_FULL])]
    protected ?\DateTimeInterface $updatedAt;

    public function getCreatedAt(): ?\DateTimeInterface
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTimeInterface $createdAt): self
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    public function getUpdatedAt(): ?\DateTimeInterface
    {
        return $this->updatedAt;
    }

    public function setUpdatedAt(\DateTimeInterface $updatedAt): self
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }
}
