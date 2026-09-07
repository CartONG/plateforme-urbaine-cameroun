<?php

namespace App\Enum;

use App\Enum\Trait\ToArray;

enum TechnicalMaturity: string
{
    use ToArray;
    case SCOPING = 'scoping';           // Cadrage
    case FEASIBILITY = 'feasibility';   // Faisabilité
    case FILE_READY = 'file_ready';     // Dossier prêt
    case MATURE = 'mature';             // Mature (Ready-to-go)
}
