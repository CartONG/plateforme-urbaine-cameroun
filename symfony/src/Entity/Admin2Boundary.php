<?php

namespace App\Entity;

use App\Entity\Actor;
use Brick\Geo\MultiPolygon;
use Brick\Geo\Io\GeoJsonWriter;
use Doctrine\ORM\Mapping as ORM;
use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\GetCollection;
use App\Repository\Admin2BoundaryRepository;
use Symfony\Component\Serializer\Attribute\Groups;

#[ORM\Entity(repositoryClass: Admin2BoundaryRepository::class)]
#[ApiResource(
    operations: [
        new GetCollection(
            uriTemplate: '/admin2_boundaries',
            normalizationContext: ['groups' => [self::GET_WITHOUT_GEOM]],
            paginationEnabled: false
        ),
    ],
)]
class Admin2Boundary
{
    private const GET_WITHOUT_GEOM = 'admin2_boundaries:get_without_geom';
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups([Actor::ACTOR_READ_ITEM, self::GET_WITHOUT_GEOM, Project::GET_FULL])]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Groups([Actor::ACTOR_READ_ITEM, self::GET_WITHOUT_GEOM, Project::GET_FULL])]
    private ?string $adm2Name = null;

    #[ORM\Column(length: 255)]
    #[Groups([Actor::ACTOR_READ_ITEM, self::GET_WITHOUT_GEOM, Project::GET_FULL])]
    private ?string $adm2Pcode = null;

    #[ORM\Column(length: 255)]
    private ?string $adm1Name = null;

    #[ORM\Column(length: 255)]
    private ?string $adm1Pcode = null;

    #[ORM\Column(type: 'geometry')]
    private $geometry;

    #[Groups([Actor::ACTOR_READ_ITEM, Project::GET_FULL])]
    public function getGeometryGeoJson(): ?string
    {
        if (!$this->geometry) {
            return null;
        }

        $Polygon = MultiPolygon::fromText($this->geometry);
        $writer = new GeoJsonWriter();
        return $writer->write($Polygon);
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getAdm2Name(): ?string
    {
        return $this->adm2Name;
    }

    public function setAdm2Name(string $adm2Name): static
    {
        $this->adm2Name = $adm2Name;

        return $this;
    }

    public function getAdm2Pcode(): ?string
    {
        return $this->adm2Pcode;
    }

    public function setAdm2Pcode(string $adm2Pcode): static
    {
        $this->adm2Pcode = $adm2Pcode;

        return $this;
    }

    public function getAdm1Name(): ?string
    {
        return $this->adm1Name;
    }

    public function setAdm1Name(string $adm1Name): static
    {
        $this->adm1Name = $adm1Name;

        return $this;
    }

    public function getAdm1Pcode(): ?string
    {
        return $this->adm1Pcode;
    }

    public function setAdm1Pcode(string $adm1Pcode): static
    {
        $this->adm1Pcode = $adm1Pcode;

        return $this;
    }

    public function getGeometry()
    {
        return $this->geometry;
    }

    public function setGeometry($geometry): static
    {
        $this->geometry = $geometry;

        return $this;
    }
}
