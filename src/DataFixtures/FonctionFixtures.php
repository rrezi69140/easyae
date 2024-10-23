<?php

namespace App\DataFixtures;

use App\Entity\Fonction;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;

class FonctionFixtures extends Fixture
{
    public const PREFIX = "fonction#";

    public function load(ObjectManager $manager): void
    {
        $faker = Factory::create();

        for ($i = 0; $i < 10; $i++) {
            $fonction = new Fonction();

            $fonction->setName($faker->jobTitle());  
            $fonction->setType($faker->word());     
            $fonction->setCreatedAt($faker->dateTimeBetween('-2 years', 'now'));  
            $fonction->setUpdatedAt($faker->dateTimeBetween('-1 years', 'now')); 
            $fonction->setStatus($faker->randomElement(['on', 'off']));

            $manager->persist($fonction);
            $this->addReference(self::PREFIX,$i, $fonction);
        }

        $manager->flush();
    }
}
