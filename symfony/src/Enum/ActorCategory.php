<?php
namespace App\Enum;

use App\Enum\Trait\ToArray;

enum ActorCategory: string
{
    case INSTITUTIONAL_ACTORS = 'Acteurs institutionnels';
    case COMPANIES_PUBLIC_ENTITIES = 'Entreprises et établissements publics';
    case TRAINING_RESEARCH_DATA_STRUCTURES = 'Structures de formation, recherche et production de données';
    case NON_INSTITUTIONAL_ACTORS = 'Acteurs non institutionnels';
    case BILATERAL_MULTILATERAL_PARTNERS = 'Partenaires bilatèreux et multilatèreux';
    case CONSULTATION_SPACES = 'Espaces de concertation';

    use ToArray;
}
