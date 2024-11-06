<?php

namespace App\Entity\Trait;

use App\Entity\Actor;
use App\Entity\Project;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Attribute\Groups;

trait ToValidateEntity 
{
    #[ORM\Column]
    #[Groups([Project::PROJECT_READ_ALL, Project::PROJECT_READ, Actor::ACTOR_READ_COLLECTION, Actor::ACTOR_READ_ITEM])]
    private ?bool $isValidated = false;

    public function getIsValidated(): ?bool
    {
        return $this->isValidated;
    }

    public function setIsValidated(bool $isValidated): static
    {
        $this->isValidated = $isValidated;

        return $this;
    }
}
