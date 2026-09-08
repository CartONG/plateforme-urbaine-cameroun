<?php

namespace App\Entity\File;

use ApiPlatform\Metadata\ApiProperty;
use App\Entity\Trait\TimestampableEntity;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

use App\Entity\Atlas;
use App\Entity\DiverCity\Booking;
use App\Entity\Project;
use App\Entity\QgisMap;
use App\Entity\Resource;
use App\Entity\User\User;
// use App\Entity\File\FileObject;

#[ORM\Entity]
#[ORM\Table(name: 'media_object')]
#[ORM\InheritanceType('SINGLE_TABLE')]
#[ORM\DiscriminatorColumn(name: 'type', type: 'string')]
#[ORM\DiscriminatorMap(['file' => FileObject::class, 'media' => MediaObject::class])]
abstract class AbstractObject
{
    use TimestampableEntity;

    #[ORM\Id, ORM\Column, ORM\GeneratedValue]
    protected ?int $id = null;

    #[ApiProperty(writable: false)]
    #[ORM\Column(nullable: true)]
    public ?string $filePath = null;

    #[ApiProperty(writable: false)]
    #[ORM\Column(nullable: true)]
    #[Groups([
        FileObject::READ,
        User::GROUP_GETME,
        Resource::GET_FULL,
        Atlas::GET_FULL,
        QgisMap::GET_FULL,
        Booking::GROUP_READ,
        Project::GET_FULL,
    ])]
    public ?string $originalName = null;

    #[ApiProperty(writable: false)]
    #[ORM\Column(nullable: true)]
    public ?string $mimeType = null;

    #[ApiProperty(writable: false)]
    #[ORM\Column(nullable: true)]
    public ?array $dimensions = null;

    #[ApiProperty(writable: false)]
    #[ORM\Column(nullable: true)]
    public ?int $size = null;

    public function getId(): ?int
    {
        return $this->id;
    }
}
