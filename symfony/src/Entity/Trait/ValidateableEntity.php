<?php

namespace App\Entity\Trait;

use App\Entity\Actor;
use App\Entity\Project;
use App\Entity\User;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Attribute\Groups;

trait ValidateableEntity
{
    #[ORM\Column]
    #[Groups([Actor::ACTOR_READ_ITEM, Actor::ACTOR_READ_COLLECTION, Project::PROJECT_READ, Project::PROJECT_READ_ALL, User::GROUP_READ, User::GROUP_GETME, User::GROUP_ADMIN])]
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
