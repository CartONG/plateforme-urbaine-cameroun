<?php

namespace App\Enum;

use App\Enum\Trait\ToArray;

enum ProjectFinancingType: string
{
    use ToArray;
    case OTHER = 'autre';
    case OWN_FUNDS = 'fonds_propres';
    case PUBLIC_GRANT = 'subvention_publique';
    case BANK_LOAN = 'pret_bancaire';
    case PRIVATE_INVESTMENT = 'investissement_prive';
    case CROWDFUNDING = 'crowdfunding';
    case PUBLIC_PRIVATE_PARTNERSHIP = 'partenariat_public_prive';
    case SPONSORSHIP = 'mecenat';
}
