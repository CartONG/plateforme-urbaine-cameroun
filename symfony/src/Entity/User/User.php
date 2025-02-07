<?php

namespace App\Entity\User;

use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Delete;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\GetCollection;
use ApiPlatform\Metadata\Patch;
use ApiPlatform\Metadata\Post;
use ApiPlatform\Metadata\Put;
use App\Entity\Actor;
use App\Entity\File\FileObject;
use App\Entity\Project;
use App\Entity\Resource;
use App\Entity\Trait\TimestampableEntity;
use App\Entity\Trait\ValidateableEntity;
use App\Model\Enums\UserRoles;
use App\Repository\User\UserRepository;
use App\Security\Voter\UserVoter;
use App\Services\State\Provider\CurrentUserProvider;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Serializer\Attribute\Groups;
use Symfony\Component\Serializer\Attribute\SerializedName;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: UserRepository::class)]
#[ORM\Table(name: '`user`')]
#[UniqueEntity('email')]
#[ApiResource(
    operations: [
        new Get(
            security: 'is_granted(\'IS_AUTHENTICATED_FULLY\')',
            uriTemplate: '/users/me',
            provider: CurrentUserProvider::class,
            normalizationContext: ['groups' => self::GROUP_GETME]
        ),
        new GetCollection(
            // provider: UserProvider::class // To use if we need to open to every logged user if we need to see user names associated to likes
            security: 'is_granted("ROLE_ADMIN")'
        ),
        new Post(),
        new Put(
            security: 'is_granted("'.UserVoter::EDIT.'", object)'
        ),
        new Patch(
            security: 'is_granted("'.UserVoter::EDIT.'", object)'
        ),
        new Delete(security: 'is_granted("'.UserVoter::EDIT.'", object)'),
    ],
    normalizationContext: ['groups' => [self::GROUP_READ]],
    denormalizationContext: ['groups' => [self::GROUP_WRITE]],
)]
class User implements UserInterface, PasswordAuthenticatedUserInterface
{
    use ValidateableEntity;
    use TimestampableEntity;

    public const GROUP_GETME = 'user:get_me';
    public const GROUP_READ = 'user:read';
    public const GROUP_WRITE = 'user:write';
    public const GROUP_ADMIN = 'user:admin';
    private const ACCEPTED_ROLES = [UserRoles::ROLE_USER, UserRoles::ROLE_EDITOR_ACTORS, UserRoles::ROLE_EDITOR_PROJECTS, UserRoles::ROLE_EDITOR_RESSOURCES];

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups([self::GROUP_READ, self::GROUP_GETME, Actor::ACTOR_READ_ITEM, Project::GET_FULL])]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank(groups: [self::GROUP_WRITE])]
    #[Groups([self::GROUP_READ, self::GROUP_GETME, self::GROUP_WRITE])]
    private ?string $firstName = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank(groups: [self::GROUP_WRITE])]
    #[Groups([self::GROUP_READ, self::GROUP_GETME, self::GROUP_WRITE])]
    private ?string $lastName = null;

    #[ORM\Column(length: 180)]
    #[Assert\NotBlank]
    #[Assert\Email]
    #[Groups([self::GROUP_READ, self::GROUP_GETME, self::GROUP_WRITE, UserPasswordToken::GROUP_READ])]
    private ?string $email = null;

    /**
     * @var list<string> The user roles
     */
    #[ORM\Column]
    #[Groups([self::GROUP_READ, self::GROUP_GETME, self::GROUP_ADMIN])]
    #[Assert\Choice(choices: self::ACCEPTED_ROLES, multiple: true)]
    private array $roles = [];

    /**
     * @var ?string The hashed password
     */
    #[ORM\Column(type: 'string', nullable: true)]
    private ?string $password = null;

    #[Assert\NotBlank(groups: [self::GROUP_WRITE])]
    #[Groups([self::GROUP_WRITE])]
    #[SerializedName('password')]
    private ?string $plainPassword = null;

    /**
     * @var Collection<int, Actor>
     */
    #[ORM\OneToMany(targetEntity: Actor::class, mappedBy: 'createdBy', orphanRemoval: true)]
    private Collection $actorsCreated;

    #[ORM\Column(nullable: true)]
    #[Assert\Choice(choices: self::ACCEPTED_ROLES, multiple: true)]
    #[Groups([self::GROUP_READ, self::GROUP_GETME, self::GROUP_WRITE])]
    private ?array $requestedRoles = null;

    #[ORM\Column(length: 255, nullable: true)]
    #[Groups([self::GROUP_READ, self::GROUP_GETME, self::GROUP_WRITE])]
    private ?string $organisation = null;

    #[ORM\Column(length: 255, nullable: true)]
    #[Groups([self::GROUP_READ, self::GROUP_GETME, self::GROUP_WRITE])]
    private ?string $position = null;

    #[ORM\Column(length: 20, nullable: true)]
    #[Groups([self::GROUP_READ, self::GROUP_GETME, self::GROUP_WRITE])]
    private ?string $phone = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    #[Groups([self::GROUP_READ, self::GROUP_WRITE])]
    private ?string $signUpMessage = null;

    #[ORM\OneToOne(cascade: ['persist', 'remove'])]
    #[Groups([self::GROUP_READ, self::GROUP_GETME, self::GROUP_WRITE])]
    private ?FileObject $logo = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    #[Groups([self::GROUP_READ, self::GROUP_GETME, self::GROUP_WRITE])]
    private ?string $description = null;

    /**
     * @var Collection<int, UserLike>
     */
    #[ORM\OneToMany(targetEntity: UserLike::class, mappedBy: 'userId', orphanRemoval: true)]
    private Collection $userLikes;

    public function __construct()
    {
        $this->actorsCreated = new ArrayCollection();
        $this->setRoles([UserRoles::ROLE_USER]);
        $this->userLikes = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): static
    {
        $this->email = $email;

        return $this;
    }

    /**
     * A visual identifier that represents this user.
     *
     * @see UserInterface
     */
    public function getUserIdentifier(): string
    {
        return (string) $this->email;
    }

    /**
     * @see UserInterface
     *
     * @return list<string>
     */
    public function getRoles(): array
    {
        $roles = $this->roles;
        // guarantee every user at least has ROLE_USER
        $roles[] = UserRoles::ROLE_USER;

        return array_unique($roles);
    }

    /**
     * @param list<string> $roles
     */
    public function setRoles(array $roles): static
    {
        $this->roles = $roles;

        return $this;
    }

    /**
     * @see PasswordAuthenticatedUserInterface
     */
    public function getPassword(): ?string
    {
        return $this->password;
    }

    public function setPassword(string $password): static
    {
        $this->password = $password;

        return $this;
    }

    public function getPlainPassword(): ?string
    {
        return $this->plainPassword;
    }

    public function setPlainPassword(?string $plainPassword): self
    {
        $this->plainPassword = $plainPassword;

        return $this;
    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials(): void
    {
        // If you store any temporary, sensitive data on the user, clear it here
        $this->plainPassword = null;
    }

    /**
     * @return Collection<int, Actor>
     */
    public function getActorsCreated(): Collection
    {
        return $this->actorsCreated;
    }

    public function addActorsCreated(Actor $actorsCreated): static
    {
        if (!$this->actorsCreated->contains($actorsCreated)) {
            $this->actorsCreated->add($actorsCreated);
            $actorsCreated->setCreatedBy($this);
        }

        return $this;
    }

    public function removeActorsCreated(Actor $actorsCreated): static
    {
        if ($this->actorsCreated->removeElement($actorsCreated)) {
            // set the owning side to null (unless already changed)
            if ($actorsCreated->getCreatedBy() === $this) {
                $actorsCreated->setCreatedBy(null);
            }
        }

        return $this;
    }

    public function getFirstName(): ?string
    {
        return $this->firstName;
    }

    public function setFirstName(string $firstName): static
    {
        $this->firstName = $firstName;

        return $this;
    }

    public function getLastName(): ?string
    {
        return $this->lastName;
    }

    public function setLastName(string $lastName): static
    {
        $this->lastName = $lastName;

        return $this;
    }

    #[Groups([Project::GET_FULL, Project::GET_PARTIAL, Actor::ACTOR_READ_ITEM, Resource::GET_FULL])]
    public function getFullName(): ?string
    {
        return $this->firstName.' '.$this->lastName;
    }

    public function getRequestedRoles(): ?array
    {
        return $this->requestedRoles;
    }

    public function setRequestedRoles(?array $requestedRoles): static
    {
        $this->requestedRoles = $requestedRoles;

        return $this;
    }

    public function getOrganisation(): ?string
    {
        return $this->organisation;
    }

    public function setOrganisation(?string $organisation): static
    {
        $this->organisation = $organisation;

        return $this;
    }

    public function getPosition(): ?string
    {
        return $this->position;
    }

    public function setPosition(?string $position): static
    {
        $this->position = $position;

        return $this;
    }

    public function getPhone(): ?string
    {
        return $this->phone;
    }

    public function setPhone(?string $phone): static
    {
        $this->phone = $phone;

        return $this;
    }

    public function getsignUpMessage(): ?string
    {
        return $this->signUpMessage;
    }

    public function setsignUpMessage(?string $signUpMessage): static
    {
        $this->signUpMessage = $signUpMessage;

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

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): static
    {
        $this->description = $description;

        return $this;
    }

    /**
     * @return Collection<int, UserLike>
     */
    public function getUserLikes(): Collection
    {
        return $this->userLikes;
    }

    public function addUserLike(UserLike $userLike): static
    {
        if (!$this->userLikes->contains($userLike)) {
            $this->userLikes->add($userLike);
            $userLike->setUserId($this);
        }

        return $this;
    }

    public function removeUserLike(UserLike $userLike): static
    {
        if ($this->userLikes->removeElement($userLike)) {
            // set the owning side to null (unless already changed)
            if ($userLike->getUserId() === $this) {
                $userLike->setUserId(null);
            }
        }

        return $this;
    }
}
