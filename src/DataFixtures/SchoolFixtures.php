<?php

namespace App\DataFixtures;

use App\Entity\School;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;

class SchoolFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
		// creation du faker
		$faker = \Faker\Factory::create('fr_FR');
	
		// on boucle pour hydrater des School a partir du faker
		for ($i = 0; $i < 5; $i++)
		{
			$school = new School();
			$school->setName("Ecole ENI " . $faker->city);
			$manager->persist($school);
		}
		$manager->flush();
    }
}
