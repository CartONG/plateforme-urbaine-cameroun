<?php

namespace App\ApiResource;

use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\GetCollection;
use App\Services\State\Provider\LatestNewsProvider;

#[ApiResource(
  paginationEnabled: false,
  operations: [
    new GetCollection(
      uriTemplate: '/news/latest',
      provider: LatestNewsProvider::class,
    )
  ]
)]
class News
{
  public int $id;

  public string $name;
  public string $description;
  public $updatedAt;
  public $type;
  
  public function setId(int $id)
  {
      $this->id = $id;

      return $this;
  }

}