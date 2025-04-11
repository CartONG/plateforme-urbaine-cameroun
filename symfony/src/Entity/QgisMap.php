<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Delete;
use ApiPlatform\Metadata\GetCollection;
use ApiPlatform\Metadata\Patch;
use ApiPlatform\Metadata\Post;
use App\Entity\File\FileObject;
use App\Entity\Trait\TimestampableEntity;
use App\Enum\QgisMapType;
use App\Repository\QgisMapRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Attribute\Groups;

#[ORM\Entity(repositoryClass: QgisMapRepository::class)]
#[ApiResource(
    operations: [
        new GetCollection(security: 'is_granted("ROLE_ADMIN")'),
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
    normalizationContext: ['groups' => [self::GET_FULL, QgisProject::READ, Atlas::GET_FULL]],
    denormalizationContext: ['groups' => [self::WRITE]],
)]
class QgisMap
{
    use TimestampableEntity;
    public const GET_FULL = 'qgis_map:get:full';
    public const WRITE = 'qgis_map:write';

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups([self::GET_FULL, Atlas::GET_FULL])]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Groups([self::GET_FULL, Atlas::GET_FULL, self::WRITE])]
    private ?string $name = null;

    #[ORM\ManyToOne(targetEntity: QgisProject::class)]
    #[Groups([self::GET_FULL, Atlas::GET_FULL, self::WRITE])]
    private ?QgisProject $qgisProject = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    #[Groups([self::GET_FULL, self::WRITE])]
    private ?string $description = null;

    #[ORM\Column(enumType: QgisMapType::class)]
    #[Groups([self::GET_FULL, Atlas::GET_FULL, self::WRITE])]
    private ?QgisMapType $qgisMapType = null;

    /**
     * @var Collection<int, Atlas>
     */
    #[ORM\ManyToMany(targetEntity: Atlas::class, mappedBy: 'maps')]
    private Collection $atlases;

    #[ORM\Column()]
    #[Groups([self::GET_FULL, Atlas::GET_FULL, self::WRITE])]
    private ?bool $needsToBeVisualiseAsPlainImageInsteadOfWMS = false;

    #[ORM\OneToOne(cascade: ['persist', 'remove'])]
    #[Groups([self::GET_FULL, Atlas::GET_FULL, self::WRITE])]
    private ?FileObject $logo = null;

    public function __construct()
    {
        $this->atlases = new ArrayCollection();
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

    public function getQgisProject(): ?QgisProject
    {
        return $this->qgisProject;
    }

    public function setQgisProject(?QgisProject $qgisProject): static
    {
        $this->qgisProject = $qgisProject;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): static
    {
        $this->description = $description;

        return $this;
    }

    public function getQgisMapType(): ?QgisMapType
    {
        return $this->qgisMapType;
    }

    public function setQgisMapType(QgisMapType $qgisMapType): static
    {
        $this->qgisMapType = $qgisMapType;

        return $this;
    }

    /**
     * @return Collection<int, Atlas>
     */
    public function getAtlases(): Collection
    {
        return $this->atlases;
    }

    public function addAtlas(Atlas $atlas): static
    {
        if (!$this->atlases->contains($atlas)) {
            $this->atlases->add($atlas);
            $atlas->addMap($this);
        }

        return $this;
    }

    public function removeAtlas(Atlas $atlas): static
    {
        if ($this->atlases->removeElement($atlas)) {
            $atlas->removeMap($this);
        }

        return $this;
    }

    public function isNeedsToBeVisualiseAsPlainImageInsteadOfWMS(): ?bool
    {
        return $this->needsToBeVisualiseAsPlainImageInsteadOfWMS;
    }

    public function setNeedsToBeVisualiseAsPlainImageInsteadOfWMS(bool $needsToBeVisualiseAsPlainImageInsteadOfWMS): static
    {
        $this->needsToBeVisualiseAsPlainImageInsteadOfWMS = $needsToBeVisualiseAsPlainImageInsteadOfWMS;

        return $this;
    }

    public function getLogo(): ?FileObject
    {
        return $this->logo;
    }

    public function setLogo(?FileObject $logo): static
    {
        $this->logo = $logo;

        return $this;
    }
}
