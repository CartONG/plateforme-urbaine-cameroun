<?php

namespace App\Entity;

use ApiPlatform\Doctrine\Common\Filter\SearchFilterInterface;
use ApiPlatform\Doctrine\Orm\Filter\SearchFilter;
use ApiPlatform\Metadata\ApiFilter;
use ApiPlatform\Metadata\ApiProperty;
use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Delete;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\GetCollection;
use ApiPlatform\Metadata\Patch;
use ApiPlatform\Metadata\Post;
use App\Controller\Project\SimilarProjectsAction;
use App\Entity\File\MediaObject;
use App\Entity\Trait\BlameableEntity;
use App\Entity\Trait\CreatorMessageEntity;
use App\Entity\Trait\LocalizableEntity;
use App\Entity\Trait\SluggableEntity;
use App\Entity\Trait\TimestampableEntity;
use App\Entity\Trait\ValidateableEntity;
use App\Enum\AdministrativeScope;
use App\Enum\BeneficiaryType;
use App\Enum\Status;
use App\Model\Enums\UserRoles;
use App\Repository\ProjectRepository;
use App\Services\State\Processor\ProjectProcessor;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Attribute\Groups;
use Symfony\Component\Uid\Uuid;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: ProjectRepository::class)]
#[ORM\Index(columns: ['slug', 'is_validated'], name: 'idx_project_slug_is_validated')]
#[ApiFilter(filterClass: SearchFilter::class, properties: ['slug' => SearchFilterInterface::STRATEGY_EXACT])]
#[ApiResource(
    paginationEnabled: false,
    operations: [
        new GetCollection(
            uriTemplate: '/projects/all',
            normalizationContext: ['groups' => [self::GET_PARTIAL]]
        ),
        new GetCollection(
            uriTemplate: '/projects/{id}/similar',
            controller: SimilarProjectsAction::class,
            normalizationContext: ['groups' => [self::GET_PARTIAL]]
        ),
        new GetCollection(
            uriTemplate: '/projects',
            normalizationContext: ['groups' => [self::GET_FULL, Admin1Boundary::GET_WITH_GEOM, Admin2Boundary::GET_WITH_GEOM, Admin3Boundary::GET_WITH_GEOM]]
        ),
        new Get(
            normalizationContext: ['groups' => [self::GET_PARTIAL]]
        ),
    ]
)]
#[ApiResource(
    operations: [
        new Post(
            security: "is_granted('".UserRoles::ROLE_EDITOR_PROJECTS."')",
            processor: ProjectProcessor::class
        ),
        new Patch(
            security: "is_granted('ROLE_ADMIN') or (is_granted('".UserRoles::ROLE_EDITOR_PROJECTS."') and object.getCreatedBy() == user)",
            processor: ProjectProcessor::class
        ),
        new Delete(
            security: 'is_granted("ROLE_ADMIN")',
        ),
    ],
    normalizationContext: ['groups' => [self::GET_FULL, self::GET_PARTIAL]],
    denormalizationContext: ['groups' => [self::WRITE]],
)]
class Project
{
    use TimestampableEntity;
    use BlameableEntity;
    use SluggableEntity;
    use LocalizableEntity;
    use ValidateableEntity;
    use CreatorMessageEntity;

    public const GET_FULL = 'project:get:full';
    public const GET_PARTIAL = 'project:get:partial';
    public const WRITE = 'project:write';

    #[ORM\Id]
    #[ORM\Column(type: 'uuid', unique: true)]
    #[ORM\GeneratedValue(strategy: 'CUSTOM')]
    #[ORM\CustomIdGenerator('doctrine.uuid_generator')]
    #[Groups([self::GET_FULL, self::GET_PARTIAL, Actor::ACTOR_READ_ITEM])]
    private ?Uuid $id = null;

    #[ORM\Column(length: 255)]
    #[Groups([self::GET_FULL, self::GET_PARTIAL, self::WRITE, Actor::ACTOR_READ_ITEM])]
    private ?string $name = null;

    #[ORM\Column(enumType: Status::class)]
    #[Groups([self::GET_FULL, self::GET_PARTIAL, self::WRITE])]
    private ?Status $status = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    #[Groups([self::GET_FULL, self::WRITE])]
    #[Assert\Length(max: 500)]
    private ?string $description = null;

    #[ORM\Column(type: 'simple_array', enumType: AdministrativeScope::class)]
    #[Groups([self::GET_FULL, self::GET_PARTIAL, self::WRITE])]
    private array $administrativeScopes = [];

    /**
     * @var Collection<int, Thematic>
     */
    #[ORM\ManyToMany(targetEntity: Thematic::class, inversedBy: 'projects')]
    #[Groups([self::GET_FULL, self::GET_PARTIAL, self::WRITE])]
    private Collection $thematics;

    #[ORM\Column(length: 255)]
    #[Groups([self::GET_FULL, self::GET_PARTIAL, self::WRITE])]
    #[Assert\Length(max: 100)]
    private ?string $focalPointName = null;

    #[ORM\Column(length: 255, nullable: true)]
    #[Groups([self::GET_FULL, self::WRITE])]
    #[Assert\Length(max: 100)]
    private ?string $focalPointPosition = null;

    #[ORM\Column(length: 255, nullable: true)]
    #[Groups([self::GET_FULL, self::WRITE])]
    #[Assert\Email]
    private ?string $focalPointEmail = null;

    #[ORM\Column(length: 255, nullable: true)]
    #[Groups([self::GET_FULL, self::WRITE])]
    #[Assert\Regex(pattern: '/^[0-9]{4,15}$/')]
    private ?string $focalPointTel = null;

    #[ORM\Column(length: 255, nullable: true)]
    #[Groups([self::GET_FULL, self::WRITE])]
    private ?string $focalPointPhoto = null;

    #[ORM\Column(length: 255, nullable: true)]
    #[Groups([self::GET_FULL, self::WRITE])]
    #[Assert\Url(protocols: ['https'])]
    private ?string $website = null;

    #[ORM\ManyToOne(targetEntity: MediaObject::class)]
    #[Groups([self::GET_FULL, self::GET_PARTIAL, self::WRITE])]
    private ?MediaObject $logo = null;

    #[ORM\Column(type: Types::SIMPLE_ARRAY, nullable: true)]
    #[Groups([self::GET_FULL, self::WRITE])]
    private ?array $externalImages = null;

    /**
     * @var Collection<int, MediaObject>
     */
    #[ORM\ManyToMany(targetEntity: MediaObject::class, cascade: ['remove'], orphanRemoval: true)]
    #[ApiProperty(types: ['https://schema.org/image'])]
    #[Groups([self::GET_FULL, self::WRITE])]
    private Collection $images;

    /**
     * @var Collection<int, MediaObject>
     */
    #[ORM\ManyToMany(targetEntity: MediaObject::class, cascade: ['remove'], orphanRemoval: true)]
    #[ORM\JoinTable('projects_partners_media_object')]
    #[ApiProperty(types: ['https://schema.org/image'])]
    #[Groups([self::GET_FULL, self::WRITE])]
    private Collection $partners;

    #[ORM\ManyToOne(inversedBy: 'projects')]
    #[ORM\JoinColumn(nullable: false)]
    #[Groups([self::GET_FULL, self::GET_PARTIAL, self::WRITE])]
    private ?Actor $actor = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    #[Groups([self::GET_FULL, self::WRITE])]
    #[Assert\Length(max: 500)]
    private ?string $deliverables = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    #[Groups([self::GET_FULL, self::WRITE])]
    #[Assert\Length(max: 500)]
    private ?string $calendar = null;

    #[ORM\Column(type: Types::JSON, nullable: true)]
    #[Groups([self::GET_FULL, self::GET_PARTIAL, self::WRITE])]
    private ?array $beneficiaryTypes = null;

    #[ORM\JoinTable(name: 'projects_donors')]
    #[Groups([self::GET_FULL, self::GET_PARTIAL, self::WRITE])]
    #[ORM\ManyToMany(targetEntity: Organisation::class)]
    private Collection $donors;

    #[ORM\ManyToOne]
    #[ORM\JoinColumn(nullable: false)]
    #[Groups([self::GET_FULL, self::GET_PARTIAL, self::WRITE])]
    private ?Organisation $contractingOrganisation = null;

    /**
     * @var Collection<int, Admin1Boundary>
     */
    #[ORM\ManyToMany(targetEntity: Admin1Boundary::class)]
    #[Groups([self::GET_FULL, self::GET_PARTIAL, self::WRITE])]
    private Collection $admin1List;

    /**
     * @var Collection<int, Admin2Boundary>
     */
    #[ORM\ManyToMany(targetEntity: Admin2Boundary::class)]
    #[Groups([self::GET_FULL, self::GET_PARTIAL, self::WRITE])]
    private Collection $admin2List;

    /**
     * @var Collection<int, Admin3Boundary>
     */
    #[ORM\ManyToMany(targetEntity: Admin3Boundary::class)]
    #[Groups([self::GET_FULL, self::GET_PARTIAL, self::WRITE])]
    private Collection $admin3List;

    public function __construct()
    {
        $this->thematics = new ArrayCollection();
        $this->donors = new ArrayCollection();
        $this->images = new ArrayCollection();
        $this->partners = new ArrayCollection();
        $this->administrativeScopes = [];
        $this->admin1List = new ArrayCollection();
        $this->admin2List = new ArrayCollection();
        $this->admin3List = new ArrayCollection();
    }

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

    public function getStatus(): ?Status
    {
        return $this->status;
    }

    public function setStatus(Status $status): static
    {
        $this->status = $status;

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

    public function getLogo(): ?MediaObject
    {
        return $this->logo;
    }

    public function setLogo(?MediaObject $logo): static
    {
        $this->logo = $logo;

        return $this;
    }

    /**
     * @return Collection<int, MediaObject>
     */
    public function getImages(): Collection
    {
        return $this->images;
    }

    public function addImage(MediaObject $image): static
    {
        if (!$this->images->contains($image)) {
            $this->images->add($image);
        }

        return $this;
    }

    public function removeImage(MediaObject $image): static
    {
        $this->images->removeElement($image);

        return $this;
    }

    public function getPartners(): Collection
    {
        return $this->partners;
    }

    public function addPartner(MediaObject $image): static
    {
        if (!$this->partners->contains($image)) {
            $this->partners->add($image);
        }

        return $this;
    }

    public function removePartner(MediaObject $image): static
    {
        $this->partners->removeElement($image);

        return $this;
    }

    public function getAdministrativeScopes(): ?array
    {
        return $this->administrativeScopes;
    }

    public function setAdministrativeScopes(?array $administrativeScopes): self
    {
        $this->administrativeScopes = $administrativeScopes;

        return $this;
    }

    public function addAdministrativeScope(AdministrativeScope $scope): self
    {
        if (!in_array($scope, $this->administrativeScopes ?? [], true)) {
            $this->administrativeScopes[] = $scope;
        }

        return $this;
    }

    public function removeAdministrativeScope(AdministrativeScope $scope): self
    {
        if (($key = array_search($scope, $this->administrativeScopes ?? [], true)) !== false) {
            unset($this->administrativeScopes[$key]);
        }

        return $this;
    }

    /**
     * @return Collection<int, Thematic>
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

    public function getFocalPointName(): ?string
    {
        return $this->focalPointName;
    }

    public function setFocalPointName(?string $focalPointName): static
    {
        $this->focalPointName = $focalPointName;

        return $this;
    }

    public function getFocalPointPosition(): ?string
    {
        return $this->focalPointPosition;
    }

    public function setFocalPointPosition(?string $focalPointPosition): static
    {
        $this->focalPointPosition = $focalPointPosition;

        return $this;
    }

    public function getFocalPointEmail(): ?string
    {
        return $this->focalPointEmail;
    }

    public function setFocalPointEmail(?string $focalPointEmail): static
    {
        $this->focalPointEmail = $focalPointEmail;

        return $this;
    }

    public function getFocalPointTel(): ?string
    {
        return $this->focalPointTel;
    }

    public function setFocalPointTel(?string $focalPointTel): static
    {
        $this->focalPointTel = $focalPointTel;

        return $this;
    }

    public function getFocalPointPhoto(): ?string
    {
        return $this->focalPointPhoto;
    }

    public function setFocalPointPhoto(?string $focalPointPhoto): static
    {
        $this->focalPointPhoto = $focalPointPhoto;

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

    public function getExternalImages(): ?array
    {
        return $this->externalImages;
    }

    public function setExternalImages(?array $externalImages): static
    {
        $this->externalImages = $externalImages;

        return $this;
    }

    public function getActor(): ?Actor
    {
        return $this->actor;
    }

    public function setActor(?Actor $actor): static
    {
        $this->actor = $actor;

        return $this;
    }

    public function getDeliverables(): ?string
    {
        return $this->deliverables;
    }

    public function setDeliverables(?string $deliverables): static
    {
        $this->deliverables = $deliverables;

        return $this;
    }

    public function getCalendar(): ?string
    {
        return $this->calendar;
    }

    public function setCalendar(?string $calendar): static
    {
        $this->calendar = $calendar;

        return $this;
    }

    /**
     * @return BeneficiaryType[]|null
     */
    public function getBeneficiaryTypes(): ?array
    {
        return $this->beneficiaryTypes;
    }

    public function setBeneficiaryTypes(?array $beneficiaryTypes): static
    {
        $this->beneficiaryTypes = $beneficiaryTypes;

        return $this;
    }

    /**
     * @return Collection<int, Organisation>
     */
    public function getDonors(): Collection
    {
        return $this->donors;
    }

    public function addDonor(Organisation $donor): static
    {
        if (!$this->donors->contains($donor)) {
            $this->donors->add($donor);
        }

        return $this;
    }

    public function removeDonor(Organisation $donor): static
    {
        $this->donors->removeElement($donor);

        return $this;
    }

    public function getContractingOrganisation(): ?Organisation
    {
        return $this->contractingOrganisation;
    }

    public function setContractingOrganisation(?Organisation $contractingOrganisation): static
    {
        $this->contractingOrganisation = $contractingOrganisation;

        return $this;
    }

    /**
     * @return Collection<int, Admin1Boundary>
     */
    public function getAdmin1List(): Collection
    {
        return $this->admin1List;
    }

    public function addAdmin1List(Admin1Boundary $admin1List): static
    {
        if (!$this->admin1List->contains($admin1List)) {
            $this->admin1List->add($admin1List);
        }

        return $this;
    }

    public function removeAdmin1List(Admin1Boundary $admin1List): static
    {
        $this->admin1List->removeElement($admin1List);

        return $this;
    }

    /**
     * @return Collection<int, Admin2Boundary>
     */
    public function getAdmin2List(): Collection
    {
        return $this->admin2List;
    }

    public function addAdmin2List(Admin2Boundary $admin2List): static
    {
        if (!$this->admin2List->contains($admin2List)) {
            $this->admin2List->add($admin2List);
        }

        return $this;
    }

    public function removeAdmin2List(Admin2Boundary $admin2List): static
    {
        $this->admin2List->removeElement($admin2List);

        return $this;
    }

    /**
     * @return Collection<int, Admin3Boundary>
     */
    public function getAdmin3List(): Collection
    {
        return $this->admin3List;
    }

    public function addAdmin3List(Admin3Boundary $admin3List): static
    {
        if (!$this->admin3List->contains($admin3List)) {
            $this->admin3List->add($admin3List);
        }

        return $this;
    }

    public function removeAdmin3List(Admin3Boundary $admin3List): static
    {
        $this->admin3List->removeElement($admin3List);

        return $this;
    }
}
