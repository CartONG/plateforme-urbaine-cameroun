<?php

namespace App\Enum;

use App\Enum\Trait\ToArray;

enum AdministrativeScope: string
{
    use ToArray;
    case INTERNATIONAL = 'international';
    case NATIONAL = 'national';
    case REGIONAL = 'regional';
    case CITY = 'city';
}
