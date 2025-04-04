<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\GetCollection;
use ApiPlatform\Metadata\Patch;
use ApiPlatform\Metadata\Post;
use App\Controller\Comment\BulkDeleteCommentsController;
use App\Entity\Trait\BlameableEntity;
use App\Entity\Trait\TimestampableEntity;
use App\Enum\AppContentCommentOrigin;
use App\Repository\AppContentCommentRepository;
use App\Services\State\Processor\Comments\BulkUpdateCommentDTO;
use App\Services\State\Processor\Comments\BulkUpdateCommentProcessor;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Jsor\Doctrine\PostGIS\Types\PostGISType;
use Symfony\Component\Serializer\Attribute\Groups;

#[ORM\Entity(repositoryClass: AppContentCommentRepository::class)]
#[ApiResource(
    operations: [
        new Patch(
            uriTemplate: '/app_content_comments/bulk_update',
            name: 'bulk_update_comments',
            input: BulkUpdateCommentDTO::class,
            output: false,
            processor: BulkUpdateCommentProcessor::class,
            denormalizationContext: ['groups' => [self::COMMENT_BULK_UPDATE]],
            security: 'is_granted("ROLE_ADMIN")'
        ),
        new Post(
            uriTemplate: '/app_content_comments/bulk_delete',
            name: 'bulk_delete_comments',
            controller: BulkDeleteCommentsController::class,
            security: 'is_granted("ROLE_ADMIN")'
        ),
        new Post(security: 'is_granted("IS_AUTHENTICATED_FULLY")'),
        new GetCollection(security: 'is_granted("ROLE_ADMIN")'),
    ],
    normalizationContext: ['groups' => [self::COMMENT_READ]],
    denormalizationContext: ['groups' => [self::COMMENT_WRITE]],
)]
class AppContentComment
{
    use TimestampableEntity;
    use BlameableEntity;
    public const COMMENT_READ = 'comment_read';
    private const COMMENT_WRITE = 'comment_write';
    public const COMMENT_BULK_UPDATE = 'comment_bulk_update';

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups([self::COMMENT_READ])]
    private ?int $id = null;

    #[ORM\Column]
    #[Groups([self::COMMENT_READ, self::COMMENT_WRITE, self::COMMENT_BULK_UPDATE])]
    private ?bool $readByAdmin = false;

    #[ORM\Column(enumType: AppContentCommentOrigin::class)]
    #[Groups([self::COMMENT_READ, self::COMMENT_WRITE, self::COMMENT_BULK_UPDATE])]
    private ?AppContentCommentOrigin $origin = null;

    #[ORM\Column(type: Types::TEXT)]
    #[Groups([self::COMMENT_READ, self::COMMENT_WRITE, self::COMMENT_BULK_UPDATE])]
    private ?string $message = null;

    #[ORM\Column(
        type: PostGISType::GEOMETRY,
        options: ['geometry_type' => 'POINT'],
        nullable: true
    )]
    #[Groups([self::COMMENT_READ, self::COMMENT_WRITE, self::COMMENT_BULK_UPDATE])]
    private ?string $location = null;

    #[ORM\Column(length: 255, nullable: true)]
    #[Groups([self::COMMENT_READ, self::COMMENT_WRITE, self::COMMENT_BULK_UPDATE])]
    private ?string $originURL = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function isReadByAdmin(): ?bool
    {
        return $this->readByAdmin;
    }

    public function setReadByAdmin(bool $readByAdmin): static
    {
        $this->readByAdmin = $readByAdmin;

        return $this;
    }

    public function getOrigin(): ?AppContentCommentOrigin
    {
        return $this->origin;
    }

    public function setOrigin(AppContentCommentOrigin $origin): static
    {
        $this->origin = $origin;

        return $this;
    }

    public function getMessage(): ?string
    {
        return $this->message;
    }

    public function setMessage(string $message): static
    {
        $this->message = $message;

        return $this;
    }

    public function getLocation(): ?string
    {
        if (preg_match('/POINT\(([-\d\.]+) ([-\d\.]+)\)/', $this->location, $matches)) {
            return $matches[1].','.$matches[2];
        }

        return null;
    }

    public function setLocation(string $coords): static
    {
        // Convert lat/lng string into postgis point geometry
        if (preg_match('/^\s*(-?\d+(\.\d+)?)\s*,\s*(-?\d+(\.\d+)?)\s*$/', $coords, $matches)) {
            $lat = (float) $matches[1];
            $lng = (float) $matches[3];

            $this->location = sprintf('POINT(%f %f)', $lng, $lat);
        } else {
            throw new \InvalidArgumentException('Invalid coordinates format. Expected "lat, lng".');
        }

        return $this;
    }

    public function getOriginURL(): ?string
    {
        return $this->originURL;
    }

    public function setOriginURL(?string $originURL): static
    {
        $this->originURL = $originURL;

        return $this;
    }
}
