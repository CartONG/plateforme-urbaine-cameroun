<?php

namespace App\Enum;

use App\Enum\Trait\ToArray;

enum AppComment: string
{
    use ToArray;
    case ACTOR = 'Actor';
    case PROJECT = 'Project';
    case RESOURCE = 'Resource';
    case MAP = 'Map';
}
