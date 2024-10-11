<?php

namespace App\Entity;

use App\Entity\Thematic;
use App\Enum\ActorCategory;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\Put;
use App\Enum\ActorCategory;
use ApiPlatform\Metadata\Post;
use App\Entity\ActorExpertise;
use App\Model\Enums\UserRoles;
use Doctrine\DBAL\Types\Types;
use ApiPlatform\Metadata\Patch;
use Symfony\Component\Uid\Uuid;
use ApiPlatform\Metadata\Delete;
use Doctrine\ORM\Mapping as ORM;
use App\Security\Voter\ActorVoter;
use App\Entity\AdministrativeScope;
use App\Repository\ActorRepository;
use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\GetCollection;
use App\Entity\Trait\TimestampableEntity;
use Doctrine\Common\Collections\Collection;
use Symfony\Bundle\SecurityBundle\Security;
use App\Services\State\Provider\ActorProvider;
use App\Services\State\Processor\ActorProcessor;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

#[ORM\Entity(repositoryClass: ActorRepository::class)]
#[UniqueEntity('name')]
#[ApiResource(
    operations: [
        new GetCollection(
            provider: ActorProvider::class,
            normalizationContext: ['groups' => self::ACTOR_READ_ITEM_COLLECTION]
        ),
        new Get(),
        new Post(
            processor: ActorProcessor::class,
            security: "is_granted('".UserRoles::ROLE_EDITOR_ACTORS."')"
        ),
        new Patch(
            processor: ActorProcessor::class,
            security: 'is_granted("'.ActorVoter::EDIT.'", object)'
        ),
        new Put(
            processor: ActorProcessor::class,
            security: 'is_granted("'.ActorVoter::EDIT.'", object)'
        ),
        new Delete(
            security: 'is_granted("ROLE_ADMIN")'
        ),
    ],
    normalizationContext: ['groups' => [self::ACTOR_READ_ITEM]],
    denormalizationContext: ['groups' => [self::ACTOR_WRITE]],
)]
class Actor
{
    public const ACTOR_READ_ITEM_COLLECTION = 'actor:read_collection';
    public const ACTOR_READ_ITEM = 'actor:read_item';
    private const ACTOR_WRITE = 'actor:write';

    use TimestampableEntity;

    
    #[ORM\Id]
    #[ORM\Column(type: 'uuid', unique: true)]
    #[ORM\GeneratedValue(strategy: 'CUSTOM')]
    #[ORM\CustomIdGenerator('doctrine.uuid_generator')]
    #[Groups([self::ACTOR_READ_ITEM_COLLECTION, self::ACTOR_READ_ITEM])]
    private ?Uuid $id = null;

    #[ORM\Column(length: 255)]
    #[Groups([self::ACTOR_READ_ITEM_COLLECTION, self::ACTOR_READ_ITEM, self::ACTOR_WRITE, Project::PROJECT_READ_ALL])]
    private ?string $name = null;

    #[ORM\Column(length: 255)]
    #[Groups([self::ACTOR_READ_ITEM_COLLECTION, self::ACTOR_READ_ITEM, self::ACTOR_WRITE, Project::PROJECT_READ_ALL])]
    private ?string $acronym = null;

    #[ORM\ManyToOne(inversedBy: 'actorsCreated')]
    #[ORM\JoinColumn(nullable: false)]
    #[Groups([self::ACTOR_READ_ITEM])]
    private ?User $createdBy = null;

    #[ORM\Column]
    #[Groups([self::ACTOR_READ_ITEM, self::ACTOR_READ_ITEM_COLLECTION])]
    private ?bool $isValidated = false;

    #[ORM\Column(enumType: ActorCategory::class)]
    #[Groups([self::ACTOR_READ_ITEM_COLLECTION, self::ACTOR_READ_ITEM, self::ACTOR_WRITE])]
    private ?ActorCategory $category = null;

    /**
     * @var Collection<int, ActorExpertise>
     */
    #[ORM\ManyToMany(targetEntity: ActorExpertise::class, inversedBy: 'actors')]
    #[Groups([self::ACTOR_READ_ITEM_COLLECTION, self::ACTOR_READ_ITEM, self::ACTOR_WRITE])]
    private Collection $expertises;

    /**
     * @var Collection<int, Thematics>
     */
    #[ORM\ManyToMany(targetEntity: Thematic::class, inversedBy: 'actors')]
    #[Groups([self::ACTOR_READ_ITEM, self::ACTOR_WRITE])]
    private Collection $thematics;

    #[ORM\Column(type: Types::TEXT)]
    #[Groups([self::ACTOR_READ_ITEM, self::ACTOR_WRITE])]
    private ?string $description = null;

    #[ORM\Column(length: 255, nullable: true)]
    #[Groups([self::ACTOR_READ_ITEM, self::ACTOR_WRITE])]
    private ?string $officeName = null;

    #[ORM\Column(length: 255, nullable: true)]
    #[Groups([self::ACTOR_READ_ITEM, self::ACTOR_WRITE])]
    private ?string $officeAddress = null;

    #[ORM\Column(length: 255, nullable: true)]
    #[Groups([self::ACTOR_READ_ITEM, self::ACTOR_WRITE])]
    private ?string $contactName = null;

    #[ORM\Column(length: 255, nullable: true)]
    #[Groups([self::ACTOR_READ_ITEM, self::ACTOR_WRITE])]
    private ?string $contactPosition = null;

    #[ORM\Column(length: 255, nullable: true)]
    #[Groups([self::ACTOR_READ_ITEM, self::ACTOR_WRITE])]
    private ?string $website = null;

    #[ORM\Column(length: 255, nullable: true)]
    #[Groups([self::ACTOR_READ_ITEM, self::ACTOR_WRITE])]
    private ?string $phone = null;

    #[ORM\Column(length: 255, nullable: true)]
    #[Groups([self::ACTOR_READ_ITEM, self::ACTOR_WRITE])]
    #[Assert\Email]
    private ?string $email = null;

    /**
     * @var Collection<int, Project>
     */
    #[ORM\OneToMany(targetEntity: Project::class, mappedBy: 'actor')]
    #[Groups([self::ACTOR_READ_ITEM, self::ACTOR_WRITE])]
    private Collection $projects;

    #[ORM\Column(length: 255, nullable: true)]
    #[Groups([self::ACTOR_READ_ITEM_COLLECTION,self::ACTOR_READ_ITEM, self::ACTOR_WRITE])]
    private ?string $logo = null;

    /**
     * @var Collection<int, AdministrativeScope>
     */
    #[ORM\ManyToMany(targetEntity: AdministrativeScope::class, inversedBy: 'actors')]
    #[Groups([self::ACTOR_READ_ITEM, self::ACTOR_WRITE])]
    private Collection $administrativeScopes;

    public function __construct()
    {
        $this->expertises = new ArrayCollection();
        $this->thematics = new ArrayCollection();
        $this->projects = new ArrayCollection();
        $this->administrativeScopes = new ArrayCollection();
    }

    public function getId(): ?Uuid {
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

    public function getAcronym(): ?string
    {
        return $this->acronym;
    }

    public function setAcronym(string $acronym): static
    {
        $this->acronym = $acronym;

        return $this;
    }

    public function getCreatedBy(): ?User
    {
        return $this->createdBy;
    }

    public function setCreatedBy(?User $createdBy): static
    {
        $this->createdBy = $createdBy;

        return $this;
    }

    public function getIsValidated(): ?bool
    {
        return $this->isValidated;
    }

    public function setIsValidated(bool $isValidated): static
    {
        $this->isValidated = $isValidated;

        return $this;
    }

    public function getCategory(): ?ActorCategory
    {
        return $this->category;
    }

    public function setCategory(ActorCategory $category): static
    {
        $this->category = $category;

        return $this;
    }

    /**
     * @return Collection<int, ActorExpertise>
     */
    public function getExpertises(): Collection
    {
        return $this->expertises;
    }

    public function addExpertise(ActorExpertise $expertise): static
    {
        if (!$this->expertises->contains($expertise)) {
            $this->expertises->add($expertise);
        }

        return $this;
    }

    public function removeExpertise(ActorExpertise $expertise): static
    {
        $this->expertises->removeElement($expertise);

        return $this;
    }

    /**
     * @return Collection<int, Thematics>
     */
    public function getThematics(): Collection
    {
        return $this->thematics;
    }

    public function addThematic(Thematic $thematic): static
    {
        if (!$this->thematics->contains($thematic)) {
            $this->thematics->add($thematic);
        }

        return $this;
    }

    public function removeThematic(Thematic $thematic): static
    {
        $this->thematics->removeElement($thematic);

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

    public function getOfficeName(): ?string
    {
        return $this->officeName;
    }

    public function setOfficeName(?string $officeName): static
    {
        $this->officeName = $officeName;

        return $this;
    }

    public function getOfficeAddress(): ?string
    {
        return $this->officeAddress;
    }

    public function setOfficeAddress(?string $officeAddress): static
    {
        $this->officeAddress = $officeAddress;

        return $this;
    }

    public function getContactName(): ?string
    {
        return $this->contactName;
    }

    public function setContactName(?string $contactName): static
    {
        $this->contactName = $contactName;

        return $this;
    }

    public function getContactPosition(): ?string
    {
        return $this->contactPosition;
    }

    public function setContactPosition(?string $contactPosition): static
    {
        $this->contactPosition = $contactPosition;

        return $this;
    }

    public function getWebsite(): ?string
    {
        return $this->website;
    }

    public function setWebsite(?string $website): static
    {
        $this->website = $website;

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

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(?string $email): static
    {
        $this->email = $email;

        return $this;
    }

    /**
    * @return Collection<int, Project>
     */
    public function getProjects(): Collection
    {
        return $this->projects;
    }

    public function addProject(Project $project): static
    {
        if (!$this->projects->contains($project)) {
            $this->projects->add($project);
            $project->setActor($this);
        }

        return $this;
    }

    public function removeProject(Project $project): static
    {
        if ($this->projects->removeElement($project)) {
            // set the owning side to null (unless already changed)
            if ($project->getActor() === $this) {
                $project->setActor(null);
            }
        }

        return $this;
    }

    public function getLogo(): ?string
    {
        return $this->logo;
    }

    public function setLogo(?string $logo): static
    {
        $this->logo = $logo;

        return $this;
    }

    /**
     * @return Collection<int, AdministrativeScope>
     */
    public function getAdministrativeScopes(): Collection
    {
        return $this->administrativeScopes;
    }

    public function addAdministrativeScope(AdministrativeScope $administrativeScope): static
    {
        if (!$this->administrativeScopes->contains($administrativeScope)) {
            $this->administrativeScopes->add($administrativeScope);
        }

        return $this;
    }

    public function removeAdministrativeScope(AdministrativeScope $administrativeScope): static
    {
        $this->administrativeScopes->removeElement($administrativeScope);

        return $this;
    }
}
    