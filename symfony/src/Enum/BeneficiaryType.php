<?php
namespace App\Enum;

use App\Enum\Trait\ToArray;

enum BeneficiaryType: string
{    
    case POPULATION = 'population';
    case LOCAL_COMMUNITIES = 'local_communities';
    case COMMUNITIES_OF_COMMUNES = 'communities_of_communes';
    case REGIONS = 'regions';
    case LOCAL_INSTITUTIONS = 'local_institutions';
    case ASSOCIATIONS_NGOS = 'associations_ngos';
    
    use ToArray;
}
