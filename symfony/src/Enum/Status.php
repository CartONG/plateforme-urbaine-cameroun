<?php

namespace App\Enum;

use App\Enum\Trait\ToArray;

enum Status: string
{
    use ToArray;
    case ONGOING = 'ongoing';
    case FINALIZED = 'finalized';
}
