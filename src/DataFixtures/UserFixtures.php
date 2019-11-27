<?php


namespace App\DataFixtures;


use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use App\Entity\User;
use App\Entity\School;

class UserFixtures extends Fixture
{
    /**
     * Load data fixtures with the passed EntityManager
     */
    public function load(ObjectManager $manager)
    {
        $faker = \Faker\Factory::create('fr_FR');
        for ($count = 0; $count <20; $count++) {
            $user = new User();
            $user->setUsername($faker->userName);
            $user->setLastname($faker->lastName);
            $user->setFirstname($faker->firstName);
            $user->setEmail($faker->email);
            $user->setPassword($this->encoder->encodePassword($user, "123"));
            $user->setPhone($faker->phoneNumber);
            $user->setAvatar("avatar.jpg");
            $user->setAdmin(false);
            $user->setActive(true);
            $user->setSchool($this->getReference('school_'.rand(0, 7)));;
            $this->addReference('user_' . $count, $user);
            $manager->persist($user);
        }
        $manager->flush();

    }

}