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

            $fonction->setName($faker->jobTitle());  // Génère un nom de fonction aléatoire
            $fonction->setType($faker->word());      // Génère un mot aléatoire pour le type
            $fonction->setCreatedAt($faker->dateTimeBetween('-2 years', 'now'));  // Date aléatoire entre il y a 2 ans et aujourd'hui
            $fonction->setUpdatedAt($faker->dateTimeBetween('-1 years', 'now'));  // Date aléatoire entre il y a 1 an et aujourd'hui
            $fonction->setStatus($faker->randomElement(['on', 'off']));  // Statut aléatoire "on" ou "off"

            $manager->persist($fonction);
            $this->addReference(self::PREFIX,$i, $fonction);
        }

        $manager->flush();
    }
}
