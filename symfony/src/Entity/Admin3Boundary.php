<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\GetCollection;
use App\Repository\Admin3BoundaryRepository;
use Brick\Geo\Io\GeoJsonWriter;
use Brick\Geo\MultiPolygon;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Attribute\Groups;

#[ORM\Entity(repositoryClass: Admin3BoundaryRepository::class)]
#[ApiResource(
    operations: [
        new GetCollection(
            uriTemplate: '/admin3_boundaries',
            normalizationContext: ['groups' => [self::GET_WITHOUT_GEOM]],
            paginationEnabled: false
        ),
    ],
)]
class Admin3Boundary
{
    public const GET_WITH_GEOM = 'admin1_boundaries:get_with_geom';
    private const GET_WITHOUT_GEOM = 'admin3_boundaries:get_without_geom';

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups([self::GET_WITH_GEOM, self::GET_WITHOUT_GEOM])]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Groups([self::GET_WITH_GEOM, self::GET_WITHOUT_GEOM])]
    private ?string $adm3Name = null;

    #[ORM\Column(length: 255)]
    #[Groups([self::GET_WITH_GEOM, self::GET_WITHOUT_GEOM])]
    private ?string $adm3Pcode = null;

    #[ORM\Column(length: 255)]
    private ?string $adm2Name = null;

    #[ORM\Column(length: 255)]
    private ?string $adm2Pcode = null;

    #[ORM\Column(length: 255)]
    private ?string $adm1Name = null;

    #[ORM\Column(length: 255)]
    private ?string $adm1Pcode = null;

    #[ORM\Column(type: 'geometry')]
    private $geometry;

    #[Groups([self::GET_WITH_GEOM])]
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

    public function getAdm3Name(): ?string
    {
        return $this->adm3Name;
    }

    public function setAdm3Name(string $adm3Name): static
    {
        $this->adm3Name = $adm3Name;

        return $this;
    }

    public function getAdm3Pcode(): ?string
    {
        return $this->adm3Pcode;
    }

    public function setAdm3Pcode(string $adm3Pcode): static
    {
        $this->adm3Pcode = $adm3Pcode;

        return $this;
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
