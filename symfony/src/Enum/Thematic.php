<?php

namespace App\Enum;

use App\Enum\Trait\ToArray;

enum Thematic: string
{
    use ToArray;
    case ADMINISTRATION = 'Administration';
    case SOCIAL_AFFAIRS = 'Affaires sociales';
    case AGRICULTURE_RURAL = 'Agriculture & rural';
    case ART_CULTURE = 'Art & Culture';
    case COMMERCE = 'Commerce';
    case COMMUNICATION = 'Communication';
    case LOCAL_DEVELOPMENT = 'Développement Local';
    case DEFENSE_SECURITY = 'Défense & Sécurité';
    case LAND_URBAN_PLANNING = 'Foncier et Urbanisme';
    case WATER_ENERGY = 'Eau & énergie';
    case BASIC_EDUCATION = 'Éducation de base';
    case LIVESTOCK_FISHING = 'Élevage & pêche';
    case EMPLOYMENT_TRAINING = 'Emploi & formation';
    case HIGHER_EDUCATION = 'Enseignement Supérieur';
    case SECONDARY_EDUCATION = 'Enseignement Secondaire';
    case SUSTAINABLE_DEVELOPMENT = 'Développement durable';
    case WOMEN_FAMILY = 'Femme & famille';
    case ECONOMY_FINANCE = 'Économie & Finances';
    case FORESTS_WILDLIFE = 'Forêts & faune';
    case YOUTH_CIVISM = 'Jeunesse & civisme';
    case LAW_JUSTICE = 'Droit & Justice';
    case PUBLIC_PROCUREMENT = 'Marchés publics';
    case MINING_INDUSTRY = 'Mines & industrie';
    case DIGITAL = 'Numérique';
    case RESEARCH_INNOVATION = 'Recherche & innovation';
    case PUBLIC_SERVICE = 'Fonction publique';
    case FOREIGN_RELATIONS = 'Relations extérieures';
    case HEALTH = 'Santé';
    case SPORT = 'Sport';
    case TRANSPORT_MOBILITY = 'Transport & mobilité';
    case LABOR_SOCIAL_SECURITY = 'Travail & sécurité sociale';
    case PUBLIC_WORKS = 'Travaux publics';
    case CONSULTATION = 'Concertation';
    case TRAINING = 'Formation';
    case MAJOR_PROJECTS = 'Grand projets';
    case INTERNATIONAL_SOLIDARITY = 'Solidarité internationale';
    case OTHERS = 'autres';
}
