<?php

namespace App\Enum;

use App\Enum\Trait\ToArray;

enum QgisMapType: string
{
    use ToArray;
    case RASTER = 'Raster';
    case VECTOR = 'Vector';
}
