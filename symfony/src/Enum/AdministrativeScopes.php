<?php
namespace App\Enum;

use App\Enum\Trait\ToArray;

enum AdministrativeScopes: string
{
  case NATIONAL = 'NATIONAL';
  case REGIONAL = 'REGIONAL';
  case STATE = 'STATE';
  case CITY = 'CITY';

  use ToArray;
}