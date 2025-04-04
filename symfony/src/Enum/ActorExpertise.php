<?php

namespace App\Enum;

use App\Enum\Trait\ToArray;

enum ActorExpertise: string
{
    use ToArray;
    case LAND_PLANNING = 'Planification urbaine';
    case NATION_PLANNING = 'Elaboration et mise en œuvre de la politique algorithmique de la nation ainsi que de l’aménagement du territoire';
    case GEO = 'Production de l’Information Géospatiale';
    case OTHERS = 'Autre';
}
