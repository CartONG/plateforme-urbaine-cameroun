<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Delete;
use ApiPlatform\Metadata\GetCollection;
use ApiPlatform\Metadata\Patch;
use ApiPlatform\Metadata\Post;
use App\Entity\Trait\BlameableEntity;
use App\Entity\Trait\TimestampableEntity;
use App\Entity\Trait\ValidateableEntity;
use App\Enum\ResourceType;
use App\Repository\ResourceRepository;
use App\Services\State\Processor\ResourceProcessor;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Attribute\Groups;
use Symfony\Component\Uid\Uuid;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: ResourceRepository::class)]
#[ApiResource(
    paginationEnabled: false,
    operations: [
        new GetCollection(
            normalizationContext: ['groups' => [self::RESOURCE_READ_ALL]]
        ),
    ]
)]
#[ApiResource(
    operations: [
        new Post(
            security: 'is_granted("ROLE_ADMIN")',
            processor: ResourceProcessor::class
        ),
        new Patch(
            security: 'is_granted("ROLE_ADMIN") or object.getCreatedBy() == user',
            processor: ResourceProcessor::class
        ),
        new Delete(
            security: 'is_granted("ROLE_ADMIN") or object.getCreatedBy() == user',
        ),
    ],
    normalizationContext: ['groups' => [self::RESOURCE_READ, self::RESOURCE_READ_ALL]],
    denormalizationContext: ['groups' => [self::RESOURCE_WRITE]],
)]
class Resource
{
    use TimestampableEntity;
    use BlameableEntity;
    use ValidateableEntity;
    
    public const RESOURCE_READ = 'resource:read';
    public const RESOURCE_READ_ALL = 'resource:read:all';
    public const RESOURCE_WRITE = 'resource:write';

    #[ORM\Id]
    #[ORM\Column(type: 'uuid', unique: true)]
    #[ORM\GeneratedValue(strategy: 'CUSTOM')]
    #[ORM\CustomIdGenerator('doctrine.uuid_generator')]
    #[Groups([self::RESOURCE_READ, self::RESOURCE_READ_ALL])]
    private ?Uuid $id = null;

    #[ORM\Column(length: 255)]
    #[Groups([self::RESOURCE_READ, self::RESOURCE_READ_ALL, self::RESOURCE_WRITE])]
    #[Assert\NotBlank]
    private ?string $name = null;

    #[ORM\Column(type: Types::TEXT)]
    #[Groups([self::RESOURCE_READ, self::RESOURCE_READ_ALL, self::RESOURCE_WRITE])]
    #[Assert\NotBlank]
    #[Assert\Length(max: 500)]
    private ?string $description = null;

    #[ORM\Column(enumType: ResourceType::class)]
    #[Groups([self::RESOURCE_READ, self::RESOURCE_READ_ALL, self::RESOURCE_WRITE])]
    private ?ResourceType $type = null;

    #[ORM\Column(length: 255, nullable: true)]
    #[Groups([self::RESOURCE_READ, self::RESOURCE_READ_ALL, self::RESOURCE_WRITE])]
    #[Assert\Url(protocols: ['https'])]
    private ?string $link = null;

    public function getId(): ?string
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

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): static
    {
        $this->description = $description;

        return $this;
    }

    public function getType(): ?ResourceType
    {
        return $this->type;
    }

    public function setType(ResourceType $type): static
    {
        $this->type = $type;

        return $this;
    }

    public function getLink(): ?string
    {
        return $this->link;
    }

    public function setLink(?string $link): static
    {
        $this->link = $link;

        return $this;
    }
}
