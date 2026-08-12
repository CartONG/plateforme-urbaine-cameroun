<?php

namespace App\ApiResource\DiverCity;

use ApiPlatform\Metadata\ApiProperty;
use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\GetCollection;
use ApiPlatform\Metadata\QueryParameter;
use App\Services\State\Provider\DiverCity\SpaceAvailabilityProvider;
use Symfony\Component\Serializer\Attribute\Groups;

/**
 * Représente une période d'indisponibilité sur un espace : soit une
 * réservation déjà acceptée, soit une période bloquée par un administrateur.
 *
 * N'est stocké dans aucune table : calculé à la volée par
 * SpaceAvailabilityProvider à partir de divercity.booking et
 * divercity.blocked_period.
 */
#[ApiResource(
    paginationEnabled: false,
    operations: [
        new GetCollection(
            uriTemplate: '/divercity/spaces/{spaceId}/availability',
            provider: SpaceAvailabilityProvider::class,
            normalizationContext: ['groups' => [self::GROUP_READ]],
            parameters: [
                'date_from' => new QueryParameter(),
                'date_to' => new QueryParameter(),
            ],
        ),
    ]
)]
class SpaceAvailability
{
    public const GROUP_READ = 'divercity_space_availability:read';

    public const TYPE_BOOKING = 'booking';
    public const TYPE_BLOCKED_PERIOD = 'blocked_period';

    #[ApiProperty(identifier: true)]
    #[Groups([self::GROUP_READ])]
    private string $id;

    #[Groups([self::GROUP_READ])]
    private \DateTimeInterface $date;

    #[Groups([self::GROUP_READ])]
    private \DateTimeInterface $startTime;

    #[Groups([self::GROUP_READ])]
    private \DateTimeInterface $endTime;

    /**
     * TYPE_BOOKING ou TYPE_BLOCKED_PERIOD — permet au front de distinguer
     * une réservation d'une indisponibilité administrative.
     */
    #[Groups([self::GROUP_READ])]
    private string $type;

    public function getId(): string
    {
        return $this->id;
    }

    public function setId(string $id): self
    {
        $this->id = $id;

        return $this;
    }

    public function getDate(): \DateTimeInterface
    {
        return $this->date;
    }

    public function setDate(\DateTimeInterface $date): self
    {
        $this->date = $date;

        return $this;
    }

    public function getStartTime(): \DateTimeInterface
    {
        return $this->startTime;
    }

    public function setStartTime(\DateTimeInterface $startTime): self
    {
        $this->startTime = $startTime;

        return $this;
    }

    public function getEndTime(): \DateTimeInterface
    {
        return $this->endTime;
    }

    public function setEndTime(\DateTimeInterface $endTime): self
    {
        $this->endTime = $endTime;

        return $this;
    }

    public function getType(): string
    {
        return $this->type;
    }

    public function setType(string $type): self
    {
        $this->type = $type;

        return $this;
    }
}