<?php

namespace App\Entity\Administrative;

use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\GetCollection;
use App\Repository\AdministrativeScopeRepository;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Attribute\Groups;

#[ORM\Entity(repositoryClass: AdministrativeScopeRepository::class)]
#[ApiResource(
    operations: [
        new GetCollection(),
    ],
    normalizationContext: ['groups' => [self::GROUP_READ]],
    denormalizationContext: ['groups' => [self::GROUP_WRITE]],
)]
class AdministrativeArea
{
    private const string GROUP_READ = 'administrative-area:read';
    private const string GROUP_WRITE = 'administrative-area:write';

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Groups([self::GROUP_READ, self::GROUP_WRITE])]
    private ?string $name = null;

    #[ORM\Column(length: 255)]
    #[Groups([self::GROUP_READ, self::GROUP_WRITE])]
    private ?string $nameEn = null;

    #[ORM\Column(length: 50)]
    #[Groups([self::GROUP_READ, self::GROUP_WRITE])]
    private ?string $code = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $createdAt = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $validatedAt = null;

    #[ORM\ManyToOne(targetEntity: AdministrativeArea::class, inversedBy: 'children')]
    private ?AdministrativeArea $parent = null;

    #[ORM\OneToMany(targetEntity: AdministrativeArea::class, mappedBy: 'parent')]
    private Collection $children;

    #[ORM\ManyToOne(targetEntity: AdministrativeScope::class, inversedBy: 'administrativeAreas')]
    private ?AdministrativeScope $administrativeScope;
}
