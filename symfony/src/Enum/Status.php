<?php
namespace App\Enum;

use App\Enum\Trait\ToArray;

enum Status: string
{
  case ONGOING = 'ongoing';
  case FINALIZED = 'finalized';

  use ToArray;
}