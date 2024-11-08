<?php

namespace App\Entity;

use ApiPlatform\Doctrine\Common\Filter\SearchFilterInterface;
use ApiPlatform\Doctrine\Orm\Filter\SearchFilter;
use ApiPlatform\Metadata\ApiFilter;
use ApiPlatform\Metadata\GetCollection;
use App\Enum\AdministrativeScope;
use App\Enum\Status;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\QueryParameter;
use App\Controller\Project\SimilarProjectsAction;
use App\Repository\ProjectRepository;
use App\Entity\Trait\SluggableEntity;
use App\Entity\Trait\TimestampableEntity;
use App\Entity\Trait\BlameableEntity;
use Doctrine\Common\Collections\Collection;
use Jsor\Doctrine\PostGIS\Types\PostGISType;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Serializer\Attribute\Groups;
use Symfony\Component\Uid\Uuid;

#[ORM\Entity(repositoryClass: ProjectRepository::class)]
#[ApiFilter(filterClass: SearchFilter::class, properties: ['slug' => SearchFilterInterface::STRATEGY_EXACT])]
#[ApiResource(
    paginationEnabled: false,
    operations: [
        new GetCollection(
            uriTemplate: '/projects/all',
            normalizationContext: ['groups' => [self::PROJECT_READ_ALL]]
        ),
        new GetCollection(
            normalizationContext: ['groups' => [self::PROJECT_READ]],
            parameters: [
                'slug' => new QueryParameter()
            ]
        ),
        new GetCollection(
            uriTemplate: '/projects/{id}/similar',
            controller: SimilarProjectsAction::class,
            normalizationContext: ['groups' => [self::PROJECT_READ_ALL]]
        ),
    ]
)]
class Project
{
    use TimestampableEntity;
    use BlameableEntity;
    use SluggableEntity;

    public const PROJECT_READ = 'project:read';
    public const PROJECT_READ_ALL = 'project:read:all';

    #[ORM\Id]
    #[ORM\Column(type: 'uuid', unique: true)]
    #[ORM\GeneratedValue(strategy: 'CUSTOM')]
    #[ORM\CustomIdGenerator('doctrine.uuid_generator')]
    #[Groups([self::PROJECT_READ, self::PROJECT_READ_ALL])]
    private ?Uuid $id = null;

    #[ORM\Column(length: 255)]
    #[Groups([self::PROJECT_READ, self::PROJECT_READ_ALL, Actor::ACTOR_READ_ITEM])]
    private ?string $name = null;

    #[ORM\Column(length: 255)]
    #[Groups([self::PROJECT_READ, self::PROJECT_READ_ALL, Actor::ACTOR_READ_ITEM])]
    private ?string $location = null;

    #[ORM\Column(
        type: PostGISType::GEOMETRY, 
        options: ['geometry_type' => 'POINT'],
    )]
    #[Groups([self::PROJECT_READ, self::PROJECT_READ_ALL])]
    private ?string $coords = null;

    #[ORM\Column(enumType: Status::class)]
    #[Groups([self::PROJECT_READ, self::PROJECT_READ_ALL])]
    private ?Status $status = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    #[Groups([self::PROJECT_READ])]
    private ?string $description = null;

    #[ORM\Column(type: Types::JSON, nullable: true)]
    private ?array $images = null;

    #[ORM\Column(type: Types::JSON, nullable: true)]
    #[Groups([self::PROJECT_READ])]
    private ?array $partners = null;

    #[ORM\Column(enumType: AdministrativeScope::class)]
    #[Groups([self::PROJECT_READ_ALL])]
    private ?AdministrativeScope $interventionZone = null;

    /**
     * @var Collection<int, Thematic>
     */
    #[ORM\ManyToMany(targetEntity: Thematic::class, inversedBy: 'projects')]
    #[Groups([self::PROJECT_READ, self::PROJECT_READ_ALL])]
    private Collection $thematics;

    #[ORM\Column(length: 255, nullable: true)]
    #[Groups([self::PROJECT_READ])]
    private ?string $projectManagerName = null;

    #[ORM\Column(length: 255, nullable: true)]
    #[Groups([self::PROJECT_READ])]
    private ?string $projectManagerPosition = null;

    #[ORM\Column(length: 255, nullable: true)]
    #[Groups([self::PROJECT_READ])]
    private ?string $projectManagerEmail = null;

    #[ORM\Column(length: 255, nullable: true)]
    #[Groups([self::PROJECT_READ])]
    private ?string $projectManagerTel = null;

    #[ORM\Column(length: 255, nullable: true)]
    #[Groups([self::PROJECT_READ])]
    private ?string $projectManagerPhoto = null;

    #[ORM\Column(length: 255, nullable: true)]
    #[Groups([self::PROJECT_READ])]
    private ?string $website = null;

    #[ORM\Column(length: 255, nullable: true)]
    #[Groups([self::PROJECT_READ, self::PROJECT_READ_ALL])]
    private ?string $logo = null;

    /**
     * @var Collection<int, Actor>
     */
    #[ORM\JoinTable(name: 'financed_projects_actors')]
    #[ORM\ManyToMany(targetEntity: Actor::class)]
    #[Groups([self::PROJECT_READ_ALL])]
    private Collection $financialActors;

    /**
     * @var Collection<int, Actor>
     */
    
    #[ORM\JoinTable(name: 'contracted_projects_actors')]
    #[ORM\ManyToMany(targetEntity: Actor::class)]
    #[Groups([self::PROJECT_READ_ALL])]
    private Collection $contractingActors;

    #[ORM\ManyToOne(inversedBy: 'projects')]
    #[ORM\JoinColumn(nullable: false)]
    #[Groups([self::PROJECT_READ, self::PROJECT_READ_ALL])]
    private ?Actor $actor = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    #[Groups([self::PROJECT_READ])]
    private ?string $deliverables = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    #[Groups([self::PROJECT_READ])]
    private ?string $calendar = null;

    public function __construct()
    {
        $this->thematics = new ArrayCollection();
        $this->financialActors = new ArrayCollection();
        $this->contractingActors = new ArrayCollection();
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

    public function getLocation(): ?string
    {
        return $this->location;
    }

    public function setLocation(string $location): static
    {
        $this->location = $location;

        return $this;
    }

    public function getCoords(): ?array {
        if (preg_match('/POINT\(([-\d\.]+) ([-\d\.]+)\)/', $this->coords, $matches)) {
            return [
                'lat' => (float)$matches[1],
                'lng' => (float)$matches[2],
            ];
        }
        return null;
    }

    public function setCoords(string $coords): static
    {
        $this->coords = $coords;

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

    public function getImages(): ?array
    {
        return $this->images;
    }

    public function setImages(?array $images): static
    {
        $this->images = $images;

        return $this;
    }

    public function getPartners(): ?array
    {
        return $this->partners;
    }

    public function setPartners(?array $partners): static
    {
        $this->partners = $partners;

        return $this;
    }

    public function getInterventionZone(): ?AdministrativeScope
    {
        return $this->interventionZone;
    }

    public function setInterventionZone(AdministrativeScope $interventionZone): static
    {
        $this->interventionZone = $interventionZone;

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

    public function getProjectManagerName(): ?string
    {
        return $this->projectManagerName;
    }

    public function setProjectManagerName(?string $projectManagerName): static
    {
        $this->projectManagerName = $projectManagerName;

        return $this;
    }

    public function getProjectManagerPosition(): ?string
    {
        return $this->projectManagerPosition;
    }

    public function setProjectManagerPosition(?string $projectManagerPosition): static
    {
        $this->projectManagerPosition = $projectManagerPosition;

        return $this;
    }

    public function getProjectManagerEmail(): ?string
    {
        return $this->projectManagerEmail;
    }

    public function setProjectManagerEmail(?string $projectManagerEmail): static
    {
        $this->projectManagerEmail = $projectManagerEmail;

        return $this;
    }

    public function getProjectManagerTel(): ?string
    {
        return $this->projectManagerTel;
    }

    public function setProjectManagerTel(?string $projectManagerTel): static
    {
        $this->projectManagerTel = $projectManagerTel;

        return $this;
    }

    public function getProjectManagerPhoto(): ?string
    {
        return $this->projectManagerPhoto;
    }

    public function setProjectManagerPhoto(?string $projectManagerPhoto): static
    {
        $this->projectManagerPhoto = $projectManagerPhoto;

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
     * @return Collection<int, Actor>
     */
    public function getFinancialActors(): Collection
    {
        return $this->financialActors;
    }

    public function addFinancialActor(Actor $financialActor): static
    {
        if (!$this->financialActors->contains($financialActor)) {
            $this->financialActors->add($financialActor);
        }

        return $this;
    }

    public function removeFinancialActor(Actor $financialActor): static
    {
        $this->financialActors->removeElement($financialActor);

        return $this;
    }

    /**
     * @return Collection<int, Actor>
     */
    public function getContractingActors(): Collection
    {
        return $this->contractingActors;
    }

    public function addContractingActor(Actor $contractingActor): static
    {
        if (!$this->contractingActors->contains($contractingActor)) {
            $this->contractingActors->add($contractingActor);
        }

        return $this;
    }

    public function removeContractingActor(Actor $contractingActor): static
    {
        $this->contractingActors->removeElement($contractingActor);

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
}
