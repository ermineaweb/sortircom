<?php


namespace App\DataFixtures;


use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use App\Entity\City;

class CityFixtures extends Fixture
{

    /**
     * Load data fixtures with the passed EntityManager
     */
    public function load(ObjectManager $manager)
    {
        $faker = \Faker\Factory::create('fr_FR');

        $cities = [
            'Rennes',
            'Nantes',
            'Quimper',
            'Laval',
            'Le Mans',
            'Angers',
            'La Roche-sur-Yon',
            'Niort',
            'Lorient',
            'Vannes',
            'Saumur',
            'Cholet',
            'Brest',
            'Tours',
            'Saint-Nazaire'
        ];

        foreach ($cities as $key =>$name) {
            $city = new City();
            $city->setName($name);
            $city->setZipcode($faker->postcode);
            $manager->persist($city);
            $this->addReference('city_' . $key, $city);
        }
        $manager->flush();
    }
}