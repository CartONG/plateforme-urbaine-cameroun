<?php

namespace App\Enum;

use App\Enum\Trait\ToArray;

enum ODD: string
{
    use ToArray;
    case No_ODD = '0';
    case NO_POVERTY = '1';
    case ZERO_HUNGER = '2';
    case GOOD_HEALTH_WELL_BEING = '3';
    case QUALITY_EDUCATION = '4';
    case GENDER_EQUALITY = '5';
    case CLEAN_WATER_SANITATION = '6';
    case AFFORDABLE_CLEAN_ENERGY = '7';
    case DECENT_WORK_ECONOMIC_GROWTH = '8';
    case INDUSTRY_INNOVATION_INFRASTRUCTURE = '9';
    case REDUCED_INEQUALITIES = '10';
    case SUSTAINABLE_CITIES_COMMUNITIES = '11';
    case RESPONSIBLE_CONSUMPTION_PRODUCTION = '12';
    case CLIMATE_ACTION = '13';
    case LIFE_BELOW_WATER = '14';
    case LIFE_ON_LAND = '15';
    case PEACE_JUSTICE_STRONG_INSTITUTIONS = '16';
    case PARTNERSHIPS_FOR_GOALS = '17';
}
