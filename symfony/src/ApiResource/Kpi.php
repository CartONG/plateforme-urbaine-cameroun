<?php

namespace App\ApiResource;

use ApiPlatform\Metadata\ApiProperty;
use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\GetCollection;
use App\Enum\KpiKey;
use App\Services\State\Provider\GlobalKpiProvider;
use Symfony\Component\Serializer\Attribute\Groups;

#[ApiResource(
  paginationEnabled: false,
  operations: [
    new GetCollection(
      uriTemplate: '/kpis/global',
      provider: GlobalKpiProvider::class,
      normalizationContext: [
        'groups' => [self::KPI_READ]
      ]
    )
  ]
)]
class Kpi
{
  public const KPI_READ = 'kpi:read';

  #[ApiProperty(identifier: true)]
  #[Groups([self::KPI_READ])]
  private KpiKey $key;

  #[Groups([self::KPI_READ])]
  private int $count;

  public function getKey(): KpiKey
  {
    return $this->key;
  }

  public function setKey(KpiKey $key): self
  {
    $this->key = $key;

    return $this;
  }

  public function getCount(): int
  {
    return $this->count;
  }

  public function setCount(int $count): self
  {
    $this->count = $count;

    return $this;
  }
}