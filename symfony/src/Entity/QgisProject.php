<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiProperty;
use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\GetCollection;
use ApiPlatform\Metadata\Post;
use ApiPlatform\OpenApi\Model;
use App\Services\State\Processor\QgisProjectProcessor;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;
use Vich\UploaderBundle\Mapping\Annotation as Vich;

#[ORM\Entity]
#[ApiResource(
    normalizationContext: ['groups' => [self::READ]],
    types: ['https://schema.org/QgisProject'],
    outputFormats: ['jsonld' => ['application/ld+json']],
    operations: [
        new Get(),
        new GetCollection(),
        new Post(
            inputFormats: ['multipart' => ['multipart/form-data']],
            processor: QgisProjectProcessor::class,
            openapi: new Model\Operation(
                requestBody: new Model\RequestBody(
                    content: new \ArrayObject([
                        'multipart/form-data' => [
                            'schema' => [
                                'type' => 'object',
                                'properties' => [
                                    'file' => [
                                        'type' => 'string',
                                        'format' => 'binary',
                                    ],
                                ],
                            ],
                        ],
                    ])
                )
            )
        ),
    ]
)]
class QgisProject
{
    public const READ = 'qgis_project:read';

    #[ORM\Id, ORM\Column, ORM\GeneratedValue]
    #[Groups([self::READ])]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Groups([self::READ, QgisMap::GET_FULL, Atlas::GET_FULL])]
    private ?string $name = null;

    #[ORM\Column(nullable: true)]
    #[Groups([self::READ, QgisMap::GET_FULL, Atlas::GET_FULL])]
    private ?array $layers = null;

    #[ApiProperty(types: ['https://schema.org/contentUrl'], writable: false)]
    #[Groups([self::READ])]
    public ?string $contentUrl = null;

    #[Vich\UploadableField(mapping: 'qgis_project', fileNameProperty: 'filePath')]
    #[Assert\NotNull]
    #[Assert\File(
        maxSize: '50M',
        extensions: ['zip'],
        extensionsMessage: 'Please upload a valid file (zip)',
    )]
    public ?File $file = null;

    #[ApiProperty(writable: false)]
    #[ORM\Column(nullable: true)]
    public ?string $filePath = null;

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

    public function getLayers(): ?array
    {
        return $this->layers;
    }

    public function setLayers(?array $layers): static
    {
        $this->layers = $layers;

        return $this;
    }
}
