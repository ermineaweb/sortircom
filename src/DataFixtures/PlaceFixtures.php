<?php

namespace App\DataFixtures;

use App\Entity\Place;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;

class PlaceFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager)
    {
    	$this->
		$faker = \Faker\Factory::create('fr_FR');
	
		for ($i = 0; $i < 10; $i++) {
			$place = new Place();
			$place->setName($faker->company . " " . $faker->streetName);
			$place->setAddress($faker->address);
			$place->setLatitude($faker->latitude);
			$place->setLongitude($faker->longitude);
			$place->setCity();
			
			$manager->persist($place);
		}
	
		$manager->flush();
    }
	
	/**
	 * This method must return an array of fixtures classes
	 * on which the implementing class depends on
	 *
	 * @return array
	 */
	public function getDependencies()
	{
		return array(
			CityFixtures::class,
		);
	}
}
