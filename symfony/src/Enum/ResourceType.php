<?php
namespace App\Enum;

use App\Enum\Trait\ToArray;

enum ResourceType: string
{
    case PDF = 'pdf';
    case WEB = 'web';
    case XLSX = 'xlsx';
    case VIDEO = 'video';
    case IMAGE = 'image';
    
    use ToArray;
}
