<?php

namespace App\Entity\User;

use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Delete;
use ApiPlatform\Metadata\GetCollection;
use ApiPlatform\Metadata\Post;
use App\Repository\User\UserLikeRepository;
use App\Services\State\Provider\User\UserLikeProvider;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Uid\Uuid;

#[ORM\Entity(repositoryClass: UserLikeRepository::class)]
#[ORM\UniqueConstraint(
    name: 'unique_like_idx',
    columns: ['user_id_id', 'content_id']
)]
#[ApiResource(
    operations: [
        new GetCollection(
            provider: UserLikeProvider::class
        ),
        new Post(
            security: 'is_granted(\'IS_AUTHENTICATED_FULLY\')'
        ),
        new Delete(
            security: 'is_granted(\'IS_AUTHENTICATED_FULLY\')',
        ),
    ]
)]
class UserLike
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'userLikes')]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $userId = null;

    #[ORM\Column(type: 'uuid', nullable: false)]
    private ?Uuid $contentId = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUserId(): ?User
    {
        return $this->userId;
    }

    public function setUserId(?User $userId): static
    {
        $this->userId = $userId;

        return $this;
    }

    public function getContentId(): ?Uuid
    {
        return $this->contentId;
    }

    public function setContentId(Uuid $contentId): static
    {
        $this->contentId = $contentId;

        return $this;
    }
}
