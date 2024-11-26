<?php
namespace App\Entity\User;

use CoopTilleuls\ForgotPasswordBundle\Entity\AbstractPasswordToken;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Attribute\Groups;

#[ORM\Entity]
class UserPasswordToken extends AbstractPasswordToken
{
    public const GROUP_READ = 'user_password_token:read';

    #[ORM\Id]
    #[ORM\Column(type: Types::INTEGER, nullable: false)]
    #[ORM\GeneratedValue]
    #[Groups([self::GROUP_READ])]
    private ?int $id = null;

    #[ORM\ManyToOne(targetEntity: User::class)]
    #[ORM\JoinColumn(nullable: false)]
    #[Groups([self::GROUP_READ])]
    private ?User $user = null;

    #[Groups([self::GROUP_READ])]
    protected $expiresAt;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    /**
     * @param User $user
     */
    public function setUser($user): self
    {
        $this->user = $user;

        return $this;
    }

    #[Groups([self::GROUP_READ])]
    public function isExpired(): bool
    {
        return (new \DateTime()) > $this->expiresAt;
    }
}
