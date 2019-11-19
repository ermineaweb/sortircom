<?php

namespace App\DataFixtures;

use App\Entity\City;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        $faker = \Faker\Factory::create('fr_FR');

        for ($i = 0; $i < 40; $i++) {
            $product = new City();
            $product->setName($faker->city);
            $product->setZipcode($faker->postcode);
            $manager->persist($product);
        }


        $manager->flush();
    }
}
