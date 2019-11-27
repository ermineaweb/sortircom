<?php


namespace App\DataFixtures;

use App\Entity\City;
use App\Entity\Place;
use App\Entity\School;
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class AppNewFixtures extends Fixture
{
    private $encoder;

    public function __construct(UserPasswordEncoderInterface $passwordEncoder)
    {
        $this->encoder = $passwordEncoder;
    }
    /**
     * Load data fixtures with the passed EntityManager
     */
    public function load(ObjectManager $manager)
    {
        $faker = \Faker\Factory::create('fr_FR');

        // Création de 8 Schools
        $schools = [
            'ENI Rennes',
            'ENI Nantes',
            'ENI Quimper',
            'ENI Laval',
            'ENI Le Mans',
            'ENI Angers',
            'ENI La Roche-sur-Yon',
            'ENI Niort'
        ];
        foreach ($schools as $key =>$name) {
            $school = new School();
            $school->setName($name);
            $manager->persist($school);
            $this->addReference('school_' . $key, $school);
        }

        // Création de 15 Cities
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

        // Création de 20 Users
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
            $user->setSchool($this->getReference('school_'.rand(0,7)));;
            $this->addReference('user_' . $count, $user);
            $manager->persist($user);
        }

        // Création d'1 Admin
        $admin = new User();
        $admin->setUsername("admin");
        $admin->setLastname("admin");
        $admin->setFirstname("admin");
        $admin->setEmail("admin@mail.com");
        $admin->setPassword($this->encoder->encodePassword($admin, "123"));
        $admin->setPhone($faker->phoneNumber);
        $admin->setAvatar("avatar.jpg");
        $admin->setAdmin(true);
        $admin->setActive(true);
        $admin->setSchool($this->getReference('school_'.rand(0,7)));
        $manager->persist($admin);

        // Création de 40 Places
        for ($count = 0; $count <20; $count++) {
            $place = new Place();
            $place->setName($faker->streetName);
            $place->setCity($this->getReference('city_' . rand(0, 14)));
            $place->setAddress($faker->streetName);
            $place->setLongitude($faker->longitude);
            $place->setLatitude($faker->latitude);
            $this->addReference('place_' . rand(0,39));
            $manager->persist($place);
        }
    }
}