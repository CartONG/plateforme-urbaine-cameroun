<?php

namespace App\Enum;

use App\Enum\Trait\ToArray;

enum FinancialStatus: string
{
    use ToArray;
    case FULL_SEARCH = 'full_search';             // Recherche intégrale
    case PARTIALLY_FUNDED = 'partially_funded';   // Partiellement financé
    case FULLY_SECURED = 'fully_secured';         // Intégralement sécurisé
    case EXTENSION_SEARCH = 'extension_search';   // En recherche d'extension
}