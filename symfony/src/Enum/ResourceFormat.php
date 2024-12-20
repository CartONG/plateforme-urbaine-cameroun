<?php

namespace App\Enum;

use App\Enum\Trait\ToArray;

enum ResourceFormat: string
{
    use ToArray;
    case PDF = 'pdf';
    case WEB = 'web';
    case XLSX = 'xlsx';
    case VIDEO = 'video';
    case IMAGE = 'image';
}
