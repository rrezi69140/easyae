<?php

namespace App\DataFixtures;

use Faker\Factory;
use Faker\Generator;
use App\Entity\Contrat;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class ContratFixtures extends Fixture
{
    public const PREFIX = "contrat#";
    public const POOL_MIN = 0;
    public const POOL_MAX = 20;
    private Generator $faker;
    public function __construct()
    {
        $this->faker = Factory::create('fr_FR');
    }
    public function load(ObjectManager $manager): void
    {
        $now = new \DateTime();
        for ($count = self::POOL_MIN; $count < self::POOL_MAX; $count++) {
            $contrat = new Contrat();

            $dateCreated = $this->faker->dateTimeInInterval('-1 year', '+1 year');
            $dateUpdated = $this->faker->dateTimeBetween($dateCreated, $now);

            $dateStarted = $this->faker->dateTimeInInterval('-2 year', '+2 year');
            $dateEnded = $this->faker->dateTimeBetween($dateStarted, '+4 year');

            $contrat->setName($this->faker->numerify("Contrat-###"))
                ->setCreatedAt($dateCreated)
                ->setUpdatedAt($dateUpdated)
                ->setStatus("on")
                ->setStartAt($dateStarted)
                ->setEndAt($dateEnded)
                ->setDone($this->faker->numberBetween(0, 1))
            ;
            $manager->persist($contrat);
            $this->addReference(self::PREFIX . $count, $contrat);
        }

        $manager->flush();
    }
}
