<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\GetCollection;
use App\Repository\Admin1BoundaryRepository;
use Brick\Geo\Io\GeoJsonWriter;
use Brick\Geo\MultiPolygon;
use Doctrine\ORM\Mapping as ORM;
use Jsor\Doctrine\PostGIS\Types\PostGISType;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: Admin1BoundaryRepository::class)]
#[ApiResource(
    operations: [
        new GetCollection(
            uriTemplate: '/admin1_boundaries',
            normalizationContext: ['groups' => [self::GET_WITHOUT_GEOM]],
            paginationEnabled: false
        ),
    ],
)]
class Admin1Boundary
{
    public const GET_WITH_GEOM = 'admin1_boundaries:get_with_geom';
    private const GET_WITHOUT_GEOM = 'admin1_boundaries:get_without_geom';
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups([self::GET_WITH_GEOM, self::GET_WITHOUT_GEOM])]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Groups([self::GET_WITH_GEOM, self::GET_WITHOUT_GEOM])]
    private ?string $adm1Name = null;

    #[ORM\Column(length: 255)]
    #[Groups([self::GET_WITH_GEOM, self::GET_WITHOUT_GEOM])]
    private ?string $adm1Pcode = null;

    #[ORM\Column(type: PostGISType::GEOMETRY)]
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
