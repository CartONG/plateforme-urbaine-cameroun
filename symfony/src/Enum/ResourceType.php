<?php

namespace App\Enum;

use App\Enum\Trait\ToArray;

enum ResourceType: string
{
    use ToArray;
    case GUIDES = 'guides'; // "Formations et guides pratiques"
    case RAPPORTS = 'rapports'; // "Rapports et présentations"
    case REGULATIONS = 'regulations'; // "Réglementations et textes officiels"
    case EVENTS = 'events'; // "Événements"
}
