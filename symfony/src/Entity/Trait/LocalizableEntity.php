<?php

namespace App\Entity\Trait;

use App\Entity\Actor;
use App\Entity\GeoData;
use App\Entity\Project;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Attribute\Groups;

trait LocalizableEntity
{
    #[Groups([Project::PROJECT_WRITE, Actor::ACTOR_WRITE])]
    private ?array $osmData = null;

    #[ORM\OneToOne(cascade: ['persist', 'remove'])]
    #[Groups([Project::PROJECT_READ, Project::PROJECT_READ_ALL, Actor::ACTOR_READ_ITEM, Actor::ACTOR_READ_COLLECTION_ALL])]
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
