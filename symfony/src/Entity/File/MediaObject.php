<?php

namespace App\Entity\File;

use ApiPlatform\Metadata\ApiProperty;
use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\GetCollection;
use ApiPlatform\Metadata\Post;
use ApiPlatform\OpenApi\Model;
use App\Entity\Actor;
use App\Entity\Project;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;
use Vich\UploaderBundle\Mapping\Annotation as Vich;

#[Vich\Uploadable]
#[ORM\Entity]
#[ApiResource(
    normalizationContext: ['groups' => [self::READ]],
    types: ['https://schema.org/MediaObject'],
    outputFormats: ['jsonld' => ['application/ld+json']],
    operations: [
        new Get(),
        new GetCollection(),
        new Post(
            inputFormats: ['multipart' => ['multipart/form-data']],
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
class MediaObject extends AbstractObject
{
    private const READ = 'media_object:read';

    #[ApiProperty(types: ['https://schema.org/contentUrl'], writable: false)]
    #[Groups([self::READ, Actor::ACTOR_READ_COLLECTION, Actor::ACTOR_READ_ITEM, Project::GET_FULL, Project::GET_PARTIAL])]
    public ?string $contentUrl = null;

    #[ApiProperty(types: ['https://schema.org/contentUrl'], writable: false)]
    #[Groups([self::READ, Actor::ACTOR_READ_COLLECTION, Actor::ACTOR_READ_ITEM, Project::GET_FULL, Project::GET_PARTIAL])]
    public ?array $contentsFilteredUrl = null;

    #[Vich\UploadableField(
        mapping: 'media_object',
        fileNameProperty: 'filePath',
        originalName: 'originalName',
        mimeType: 'mimeType',
        dimensions: 'dimensions',
        size: 'size'
    )]
    #[Assert\NotNull]
    #[Assert\File(
        maxSize: '5000k',
        extensions: ['jpg', 'jpeg', 'png', 'webp', 'gif'],
        extensionsMessage: 'Please upload a valid file (jpg, jpeg, png, webp, gif)',
    )]
    public ?File $file = null;
}
