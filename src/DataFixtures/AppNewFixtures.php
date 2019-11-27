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
            'Festival de BD'
        ];
        // Création de 20 events passés
        $randomStart = random_int(7,25);
        $randomEnd = random_int(1,3);
        $randomEndCurrent = random_int(5,13);
        $randomLimitdate = random_int(2,10);
        $randomStartCurrent = random_int(0,1);
        for ($count = 0; $count < 20; $count++) {
            $PastEvents = new Event();
            $PastEvents->setName($events[random_int(0,count($events)-1)]);
            $PastEvents->setDescription($PastEvents->getName());
            $PastEvents->setMaxsize(random_int(5, 20));
            $PastEvents->setStart($faker->dateTimeThisMonth());
            $PastEvents->setEnd($faker->dateTimeInInterval($PastEvents->getStart(), '+ '. $randomEnd. ' days'));
            $PastEvents->setLimitdate($faker->dateTimeInInterval($PastEvents->getStart(), '- '. $randomLimitdate. ' days'));
            $PastEvents->setCreator($this->getReference('user_' . rand(0, 19)));
            $PastEvents->setPlace($this->getReference('place_' . rand(0, 39)));
            $PastEvents->setStatus(StatusEnum::PASSEE);
            for ($count1 = 0; $count1 < 5; $count1++) {
                $PastEvents->addUser($this->getReference('user_' . rand(0,19)));
            }
            $manager->persist($PastEvents);

        }

        // Création de 5 events passés annulés
        for ($count = 0; $count < 5; $count++) {
            $PastEventsCanceled = new Event();
            $PastEventsCanceled->setName($events[random_int(0,count($events)-1)]);
            $PastEventsCanceled->setDescription($PastEventsCanceled->getName());
            $PastEventsCanceled->setMaxsize(random_int(5, 20));
            $PastEventsCanceled->setStart($faker->dateTimeThisMonth());
            $PastEventsCanceled->setEnd($faker->dateTimeInInterval($PastEventsCanceled->getStart(), '+ '. $randomEnd. ' days'));
            $PastEventsCanceled->setLimitdate($faker->dateTimeInInterval($PastEventsCanceled->getStart(), '- '. $randomLimitdate. ' days'));
            $PastEventsCanceled->setCreator($this->getReference('user_' . rand(0, 19)));
            $PastEventsCanceled->setPlace($this->getReference('place_' . rand(0, 39)));
            $PastEventsCanceled->setStatus(StatusEnum::ANNULEE);
            for ($count1 = 0; $count1 < 5; $count1++) {
                $PastEventsCanceled->addUser($this->getReference('user_' . rand(0,19)));
            }
            $manager->persist($PastEventsCanceled);
        }

        // Création de 25 events ouverts
        for ($count = 0; $count < 25; $count++) {
            $OpenEvents = new Event();
            $OpenEvents->setName($events[random_int(0,count($events)-1)]);
            $OpenEvents->setDescription($OpenEvents->getName());
            $OpenEvents->setMaxsize(random_int(2, 20));
            $OpenEvents->setStart($faker->dateTimeInInterval($startDate = 'now','+ '. $randomStart. ' days'));
            $OpenEvents->setEnd($faker->dateTimeInInterval($OpenEvents->getStart(), $interval = '+ '. $randomEnd. ' days'));
            $OpenEvents->setLimitdate($faker->dateTimeInInterval($OpenEvents->getStart(), $interval = '- '. $randomLimitdate. ' days'));
            $OpenEvents->setCreator($this->getReference('user_' . rand(0, 19)));
            $OpenEvents->setPlace($this->getReference('place_' . rand(0, 39)));
            $OpenEvents->setStatus(StatusEnum::OUVERTE);
            for ($count1 = 0; $count1 < 5; $count1++) {
                $OpenEvents->addUser($this->getReference('user_' . rand(0,19)));
            }
            $manager->persist($OpenEvents);
        }

        // Création de 5 events ouverts annulés
        for ($count = 0; $count < 5; $count++) {
            $OpenEventsCanceled = new Event();
            $OpenEventsCanceled->setName($events[random_int(0,count($events)-1)]);
            $OpenEventsCanceled->setDescription($OpenEventsCanceled->getName());
            $OpenEventsCanceled->setMaxsize(random_int(2, 20));
            $OpenEventsCanceled->setStart($faker->dateTimeInInterval($startDate = 'now','+ '. $randomStart. ' days'));
            $OpenEventsCanceled->setEnd($faker->dateTimeInInterval($OpenEventsCanceled->getStart(), $interval = '+ '. $randomEnd. ' days'));
            $OpenEventsCanceled->setLimitdate($faker->dateTimeInInterval($OpenEventsCanceled->getStart(), $interval = '- '. $randomLimitdate. ' days'));
            $OpenEventsCanceled->setCreator($this->getReference('user_' . rand(0, 19)));
            $OpenEventsCanceled->setPlace($this->getReference('place_' . rand(0, 39)));
            $OpenEventsCanceled->setStatus(StatusEnum::ANNULEE);
            for ($count1 = 0; $count1 < 5; $count1++) {
                $OpenEventsCanceled->addUser($this->getReference('user_' . rand(0,19)));
            }
            $manager->persist($OpenEventsCanceled);
        }

        // Création de 10 events ouverts créés
        for ($count = 0; $count < 10; $count++) {
            $OpenEventsCreated = new Event();
            $OpenEventsCreated->setName($events[random_int(0,count($events)-1)]);
            $OpenEventsCreated->setDescription($OpenEventsCreated->getName());
            $OpenEventsCreated->setMaxsize(random_int(2, 20));
            $OpenEventsCreated->setStart($faker->dateTimeInInterval($startDate = 'now', '+ '. $randomStart. ' days'));
            $OpenEventsCreated->setEnd($faker->dateTimeInInterval($OpenEventsCreated->getStart(), $interval = '+ '. $randomEnd. ' days'));
            $OpenEventsCreated->setLimitdate($faker->dateTimeInInterval($OpenEventsCreated->getStart(), $interval = '- '. $randomLimitdate. ' days'));
            $OpenEventsCreated->setCreator($this->getReference('user_' . rand(0, 19)));
            $OpenEventsCreated->setPlace($this->getReference('place_' . rand(0, 39)));
            $OpenEventsCreated->setStatus(StatusEnum::CREE);
            $manager->persist($OpenEventsCreated);
        }

        // Création de 10 events en cours
        for ($count = 0; $count < 10; $count++) {
            $CurrentEvents = new Event();
            $CurrentEvents->setName($events[random_int(0,count($events)-1)]);
            $CurrentEvents->setDescription($CurrentEvents->getName());
            $CurrentEvents->setMaxsize(random_int(2, 20));
            $CurrentEvents->setStart($faker->dateTimeInInterval($startDate = 'now','- '.$randomStartCurrent. ' days'));
            $CurrentEvents->setEnd($faker->dateTimeInInterval($CurrentEvents->getStart(), $interval = '+ '. $randomEndCurrent. ' days'));
            $CurrentEvents->setLimitdate($faker->dateTimeInInterval($CurrentEvents->getStart(), $interval = '- '. $randomLimitdate. ' days'));
            $CurrentEvents->setCreator($this->getReference('user_' . rand(0, 19)));
            $CurrentEvents->setPlace($this->getReference('place_' . rand(0, 39)));
            $CurrentEvents->setStatus(StatusEnum::EN_COURS);
            for ($count1 = 0; $count1 < 5; $count1++) {
                $CurrentEvents->addUser($this->getReference('user_' . rand(0,19)));
            }
            $manager->persist($CurrentEvents);
        }

        $manager->flush();
    }
}