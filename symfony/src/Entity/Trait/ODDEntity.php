<?php

namespace App\Entity\Trait;

use App\Entity\Actor;
use App\Entity\Project;
use App\Entity\Resource;
use App\Enum\ODD;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Attribute\Groups;

trait ODDEntity
{
    #[ORM\Column(type: 'json', enumType: ODD::class, nullable: true)]
    #[Groups([Actor::ACTOR_READ_COLLECTION, Actor::ACTOR_READ_ITEM, Actor::ACTOR_WRITE, Project::GET_FULL, Project::GET_PARTIAL, Project::WRITE, Resource::GET_FULL, Resource::WRITE])]
    private ?array $odds = [];

    public function getOdds(): ?array
    {
        return $this->odds;
    }

    public function setOdds(?array $odds): self
    {
        $this->odds = $odds;

        return $this;
    }

    public function addOdd(ODD $odd): self
    {
        if (!in_array($odd, $this->odds ?? [], true)) {
            $this->odds[] = $odd;
        }

        return $this;
    }

    public function removeOdd(ODD $odd): self
    {
        if (($key = array_search($odd, $this->odds ?? [], true)) !== false) {
            unset($this->odds[$key]);
        }

        return $this;
    }
}
