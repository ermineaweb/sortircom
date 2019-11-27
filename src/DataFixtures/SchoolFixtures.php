<?php


namespace App\DataFixtures;


use App\Entity\School;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;

class SchoolFixtures extends Fixture
{

    /**
     * Load data fixtures with the passed EntityManager
     */
    public function load(ObjectManager $manager)
    {
        $faker = \Faker\Factory::create('fr_FR');
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
        $manager->flush();

    }
}