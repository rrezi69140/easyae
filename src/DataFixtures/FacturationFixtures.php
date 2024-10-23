<?php

namespace App\DataFixtures;

use Faker\Factory;
use Faker\Generator;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use App\Entity\Facturation;

class FacturationFixtures extends Fixture
{

    public const PREFIX = "account#";
    public const PPOL_MIN = 0;
    public const PPOL_MAX = 20;

    private Generator $faker;
    public function __construct()
    {
        $this->faker = Factory::create('fr_FR');
    }
    
    public function load(ObjectManager $manager): void
    {

        $now = new \DateTime();
        
        for ($i = self::PPOL_MIN; $i < self::PPOL_MAX; $i++) {
            $dateCreated = $this->faker->dateTimeInInterval('-1 year', '+1 year');
            $dateUpdated = $this->faker->dateTimeBetween($dateCreated, $now);
            $facturation = new Facturation();
            $facturation
                ->setName($this->faker->numerify('account-###'))
                ->setStatus("on")
                ->setCreatedAt($dateCreated)
                ->setUpdatedAt($dateUpdated)
            ;

            $manager->persist($facturation);
            $this->addReference(name : self::PREFIX . $i , object: $facturation);
        }

        $manager->flush();
    }
}
