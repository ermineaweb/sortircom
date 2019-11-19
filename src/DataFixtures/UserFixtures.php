<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;

class UserFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        // creation du faker
        $faker = \Faker\Factory::create('fr_FR');

        // on boucle pour hydrater des User a partir du faker
        for ($i = 0; $i < 50; $i++)
        {
            $user = new User();

            $user->setFirstName($faker->firstName);
            $user->setLastname($faker->lastName);
            $user->setAdress($faker->address);
            $manager->persist($user);
        }

        $manager->flush();
    }
    
}
