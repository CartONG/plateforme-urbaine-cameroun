<?php
namespace App\Enum;

use App\Enum\Trait\ToArray;

enum AdministrativeScope: string
{
  case NATIONAL = 'national';
  case REGIONAL = 'regional';
  case STATE = 'state';
  case CITY = 'city';

  use ToArray;
}