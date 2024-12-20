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
    #[Groups([Project::WRITE, Resource::WRITE])]
    private ?array $osmData = null;

    #[ORM\OneToOne(cascade: ['persist', 'remove'])]
    #[Groups([Project::GET_FULL, Project::GET_PARTIAL, Actor::ACTOR_READ_ITEM, Resource::GET_FULL])]
    private ?GeoData $geoData = null;

    public function getOsmData(): ?array
    {
        return $this->osmData;
    }

    public function setOsmData(?array $osmData): static
    {
        $this->osmData = $osmData;

        return $this;
    }

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
