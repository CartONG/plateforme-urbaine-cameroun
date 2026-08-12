<?php

namespace App\DataFixtures\DiverCity;

use App\Entity\DiverCity\Status;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class StatusFixtures extends Fixture
{
    public const STATUSES = [
        'EN_ATTENTE' => 'En attente',
        'EN_COURS_DE_TRAITEMENT' => 'En cours de traitement',
        'ACCEPTEE' => 'Acceptée',
        'REFUSEE' => 'Refusée',
        'ANNULEE' => 'Annulée',
    ];

    public function load(ObjectManager $manager): void
    {
        foreach (self::STATUSES as $code => $label) {
            $status = new Status();
            $status->setCode($code);
            $status->setLabel($label);
            $manager->persist($status);
        }

        $manager->flush();
    }
}