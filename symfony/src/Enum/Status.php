<?php

namespace App\Enum;

use App\Enum\Trait\ToArray;

enum Status: string
{
    use ToArray;
    case PLANNED = 'planned';
    case ONGOING = 'ongoing';
    case FINALIZED = 'finalized';
}
