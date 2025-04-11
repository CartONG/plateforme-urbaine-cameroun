<?php

namespace App\Entity\Trait;

use App\Entity\Actor;
use App\Entity\GeoData;
use App\Entity\Project;
use App\Entity\Resource;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Attribute\Groups;

trait LocalizableEntity
{
    #[ORM\OneToOne(cascade: ['persist', 'remove'])]
    #[Groups([Project::WRITE, Resource::WRITE, Actor::ACTOR_WRITE, Project::GET_FULL, Project::GET_PARTIAL, Actor::ACTOR_READ_ITEM, Actor::ACTOR_READ_COLLECTION, Resource::GET_FULL])]
    private ?GeoData $geoData = null;

    public function getGeoData(): ?GeoData
    {
        return $this->geoData;
    }

    public function setGeoData(?GeoData $geoData): static
    {
        $this->geoData = $geoData;

        return $this;
    }
}
