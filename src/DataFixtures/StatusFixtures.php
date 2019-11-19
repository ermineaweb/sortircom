<?php

namespace App\DataFixtures;

use App\Entity\Status;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;



class StatusFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        $statutes = [
            'Créée',
            'Ouverte',
            'Clôturée',
            'EnCours',
            'Passée',
            'Annulée',
        ];

    foreach($statutes as $name) {
        $status = new Status();
        $status->setName($name);
        $manager->persist($status);
    }
        $manager->flush();
    }
}
