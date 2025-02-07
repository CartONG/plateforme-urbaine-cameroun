<?php

namespace App\Enum;

use App\Enum\Trait\ToArray;

enum AtlasGroup: string
{
    use ToArray;
    case THEMATIC_DATA = 'Catalogue';
    case PREDEFINED_MAP = 'Observatoire';
}
