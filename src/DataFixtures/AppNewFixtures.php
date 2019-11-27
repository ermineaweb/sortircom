<?php


namespace App\DataFixtures;

use App\Entity\City;
use App\Entity\Event;
use App\Entity\Place;
use App\Entity\School;
use App\Entity\StatusEnum;
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
        foreach ($schools as $key => $name) {
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

        foreach ($cities as $key => $name) {
            $city = new City();
            $city->setName($name);
            $city->setZipcode($faker->postcode);
            $manager->persist($city);
            $this->addReference('city_' . $key, $city);
        }

        // Création de 20 Users
        for ($count = 0; $count < 20; $count++) {
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
            $user->setSchool($this->getReference('school_' . rand(0, 7)));;
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
        $admin->setSchool($this->getReference('school_' . rand(0, 7)));
        $manager->persist($admin);

        // Création de 40 Places
        for ($count = 0; $count < 40; $count++) {
            $place = new Place();
            $place->setName($faker->company);
            $place->setCity($this->getReference('city_' . rand(0, 14)));
            $place->setAddress($faker->streetName);
            $place->setLongitude($faker->longitude);
            $place->setLatitude($faker->latitude);
            $this->addReference('place_' . $count, $place);
            $manager->persist($place);
        }

        // Création de 200 events
        $events = [
            'Conférence',
            'Sortie en kayak',
            'Découverte nature',
            'Océanopolis',
            'Exposition',
            'Concert',
            'Pièce de théâtre',
            'Randonnée',
            'Café débat',
            'Visite patrimoine',
            'Projection-débat film',
            'Atelier d\'art plastique',
            'Atelier cuisine',
            'Atelier d\'écriture',
            'Festival de BD',
        ];
        // Création de 50 events passées
        $randomEnd = random_int(1,3);
        $randomLimitdate = random_int(2,10);
        for ($count = 0; $count < 50; $count++) {
            $sortiePassee = new Event();
            $sortiePassee->setName($events[random_int(0, count($events))]);
            $sortiePassee->setDescription($sortiePassee->getName());
            $sortiePassee->setMaxsize(random_int(5, 20));
            $sortiePassee->setStart($faker->dateTimeThisMonth());
            $sortiePassee->setEnd($faker->dateTimeInInterval($sortiePassee->getStart(), '+ '. $randomEnd. ' days'));
            $sortiePassee->setLimitdate($faker->dateTimeInInterval($sortiePassee->getStart(), '- '. $randomLimitdate. ' days'));
            $sortiePassee->setCreator($this->getReference('user_' . rand(0, 19)));
            $sortiePassee->setPlace($this->getReference('place_' . rand(0, 39)));
            $sortiePassee->setStatus(StatusEnum::PASSEE);
            $manager->persist($sortiePassee);
        }

        // Création de 50 events ouverts
        $randomEnd = random_int(1,3);
        $randomLimitdate = random_int(2,10);
        for ($count = 0; $count < 50; $count++) {
            $sortiePassee = new Event();
            $sortiePassee->setName(shuffle($events));
            $sortiePassee->setDescription($sortiePassee->getName());
            $sortiePassee->setMaxsize(random_int(2, 20));
            $sortiePassee->setStart($faker->dateTimeInInterval($startDate = new \DateTime('+ '. $randomEnd. ' days')));
            $sortiePassee->setEnd($faker->dateTimeInInterval($sortiePassee->getStart(), $interval = '+ '. $randomEnd. ' days'));


            $sortiePassee->setLimitdate($faker->dateTimeInInterval($sortiePassee->getStart(), $interval = '- '. $randomLimitdate. ' days'));



            $sortiePassee->setCreator($this->getReference('user_' . rand(0, 19)));
            $sortiePassee->setPlace($this->getReference('place_' . rand(0, 39)));
            $sortiePassee->setStatus(StatusEnum::OUVERTE);
            $manager->persist($sortiePassee);

        }


        $manager->flush();
    }
}