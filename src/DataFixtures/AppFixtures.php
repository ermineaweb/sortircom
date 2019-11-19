<?php

namespace App\DataFixtures;

use App\Entity\City;
use App\Entity\Place;
use App\Entity\School;
use App\Entity\Status;
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;

class AppFixtures extends Fixture
{
	public function load(ObjectManager $manager)
	{
		$faker = \Faker\Factory::create('fr_FR');
		
		
		// Cities
		$quimper = new City();
		$quimper->setName("Quimper");
		$quimper->setZipcode("29000");
		$manager->persist($quimper);
		$nantes = new City();
		$nantes->setName("Nantes");
		$nantes->setZipcode("44000");
		$manager->persist($nantes);
		$rennes = new City();
		$rennes->setName("Rennes");
		$rennes->setZipcode("35000");
		$manager->persist($rennes);
		
		// Places
		$place1 = new Place();
		$place1->setName("Place " . $faker->company);
		$place1->setCity($quimper);
		$place1->setAddress($faker->address);
		$place1->setLongitude($faker->longitude);
		$place1->setLatitude($faker->latitude);
		$manager->persist($place1);
		
		$place2 = new Place();
		$place2->setName("Place " . $faker->company);
		$place2->setCity($quimper);
		$place2->setAddress($faker->address);
		$place2->setLongitude($faker->longitude);
		$place2->setLatitude($faker->latitude);
		$manager->persist($place2);
		
		$place3 = new Place();
		$place3->setName("Place " . $faker->company);
		$place3->setCity($nantes);
		$place3->setAddress($faker->address);
		$place3->setLongitude($faker->longitude);
		$place3->setLatitude($faker->latitude);
		$manager->persist($place3);
		
		$place4 = new Place();
		$place4->setName("Place " . $faker->company);
		$place4->setCity($nantes);
		$place4->setAddress($faker->address);
		$place4->setLongitude($faker->longitude);
		$place4->setLatitude($faker->latitude);
		$manager->persist($place4);
		
		// Statuses
		$statutes = [
			'Créée',
			'Ouverte',
			'Clôturée',
			'EnCours',
			'Passée',
			'Annulée',
		];
		
		foreach ($statutes as $name) {
			$status = new Status();
			$status->setName($name);
			$manager->persist($status);
		}
		
		// Schools
		$eniquimper = new School();
		$eniquimper->setName("ENI " . $quimper->getName());
		$manager->persist($eniquimper);
		
		$enirennes = new School();
		$enirennes->setName("ENI " . $rennes->getName());
		$manager->persist($enirennes);
		
		$eninantes = new School();
		$eninantes->setName("ENI " . $nantes->getName());
		$manager->persist($eninantes);
		
		// Users
		$etudiant1 = new User();
		$etudiant1->setUsername($faker->userName);
		$etudiant1->setLastname($faker->lastName);
		$etudiant1->setFirstname($faker->firstName);
		$etudiant1->setEmail($faker->email);
		$etudiant1->setPassword("abc");
		$etudiant1->setPhone($faker->phoneNumber);
		$etudiant1->setAvatar("avatar.jpg");
		$etudiant1->setAdmin(false);
		$etudiant1->setActive(true);
		$etudiant1->setSchool($eniquimper);
		$manager->persist($etudiant1);
		
		$etudiant2 = new User();
		$etudiant2->setUsername($faker->userName);
		$etudiant2->setLastname($faker->lastName);
		$etudiant2->setFirstname($faker->firstName);
		$etudiant2->setEmail($faker->email);
		$etudiant2->setPassword("abc");
		$etudiant2->setPhone($faker->phoneNumber);
		$etudiant2->setAvatar("avatar.jpg");
		$etudiant2->setAdmin(false);
		$etudiant2->setActive(true);
		$etudiant2->setSchool($eniquimper);
		$manager->persist($etudiant2);
		
		$etudiant3 = new User();
		$etudiant3->setUsername($faker->userName);
		$etudiant3->setLastname($faker->lastName);
		$etudiant3->setFirstname($faker->firstName);
		$etudiant3->setEmail($faker->email);
		$etudiant3->setPassword("abc");
		$etudiant3->setPhone($faker->phoneNumber);
		$etudiant3->setAvatar("avatar.jpg");
		$etudiant3->setAdmin(false);
		$etudiant3->setActive(true);
		$etudiant3->setSchool($eninantes);
		$manager->persist($etudiant3);
		
		$etudiant4 = new User();
		$etudiant4->setUsername($faker->userName);
		$etudiant4->setLastname($faker->lastName);
		$etudiant4->setFirstname($faker->firstName);
		$etudiant4->setEmail($faker->email);
		$etudiant4->setPassword("abc");
		$etudiant4->setPhone($faker->phoneNumber);
		$etudiant4->setAvatar("avatar.jpg");
		$etudiant4->setAdmin(false);
		$etudiant4->setActive(true);
		$etudiant4->setSchool($enirennes);
		$manager->persist($etudiant4);
		
		$etudiant5 = new User();
		$etudiant5->setUsername($faker->userName);
		$etudiant5->setLastname($faker->lastName);
		$etudiant5->setFirstname($faker->firstName);
		$etudiant5->setEmail($faker->email);
		$etudiant5->setPassword("abc");
		$etudiant5->setPhone($faker->phoneNumber);
		$etudiant5->setAvatar("avatar.jpg");
		$etudiant5->setAdmin(false);
		$etudiant5->setActive(true);
		$etudiant5->setSchool($enirennes);
		$manager->persist($etudiant5);
		
		// Events
		
		$manager->flush();
	}
}