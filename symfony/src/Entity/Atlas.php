<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Delete;
use ApiPlatform\Metadata\GetCollection;
use ApiPlatform\Metadata\Patch;
use ApiPlatform\Metadata\Post;
use App\Entity\Trait\TimestampableEntity;
use App\Enum\AtlasGroup;
use App\Repository\AtlasRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Attribute\Groups;

#[ORM\Entity(repositoryClass: AtlasRepository::class)]
#[ApiResource(
    operations: [
        new GetCollection(),
        new Post(
            security: 'is_granted("ROLE_ADMIN")',
        ),
        new Patch(
            security: 'is_granted("ROLE_ADMIN")',
        ),
        new Delete(
            security: 'is_granted("ROLE_ADMIN")',
        ),
    ],
    normalizationContext: ['groups' => [self::GET_FULL]],
    denormalizationContext: ['groups' => [self::WRITE]],
)]
class Atlas
{
    use TimestampableEntity;
    public const GET_FULL = 'atlas:get:full';
    public const WRITE = 'atlas:write';

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups([self::GET_FULL])]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Groups([self::GET_FULL, self::WRITE])]
    private ?string $name = null;

    #[ORM\Column(enumType: AtlasGroup::class)]
    #[Groups([self::GET_FULL, self::WRITE])]
    private ?AtlasGroup $atlasGroup = null;

    #[ORM\Column]
    #[Groups([self::GET_FULL, self::WRITE])]
    private ?int $position = null;

    /**
     * @var Collection<int, QgisMap>
     */
    #[ORM\ManyToMany(targetEntity: QgisMap::class, inversedBy: 'atlases')]
    #[Groups([self::GET_FULL, self::WRITE])]
    private Collection $maps;

    #[ORM\OneToOne(cascade: ['persist', 'remove'])]
    #[Groups([self::GET_FULL, self::WRITE])]
    private ?MediaObject $logo = null;

    public function __construct()
    {
        $this->maps = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): static
    {
        $this->name = $name;

        return $this;
    }

    public function getAtlasGroup(): ?AtlasGroup
    {
        return $this->atlasGroup;
    }

    public function setAtlasGroup(AtlasGroup $atlasGroup): static
    {
        $this->atlasGroup = $atlasGroup;

        return $this;
    }

    public function getPosition(): ?int
    {
        return $this->position;
    }

    public function setPosition(int $position): static
    {
        $this->position = $position;

        return $this;
    }

    /**
     * @return Collection<int, QgisMap>
     */
    public function getMaps(): Collection
    {
        return $this->maps;
    }

    public function addMap(QgisMap $map): static
    {
        if (!$this->maps->contains($map)) {
            $this->maps->add($map);
        }

        return $this;
    }

    public function removeMap(QgisMap $map): static
    {
        $this->maps->removeElement($map);

        return $this;
    }

    public function getLogo(): ?MediaObject
    {
        return $this->logo;
    }

    public function setLogo(?MediaObject $logo): static
    {
        $this->logo = $logo;

        return $this;
    }
}
