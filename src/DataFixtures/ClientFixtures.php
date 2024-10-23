<?php

namespace App\DataFixtures;

use Faker\Factory;
use Faker\Generator;
use App\Entity\Client;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;

class ClientFixtures extends Fixture
{
    public const PREFIX = "client#";
    public const POOL_MIN = 0;
    public const POOL_MAX = 10;

    private Generator $faker;

    public function __construct()
    {
        $this->faker = Factory::create('fr_FR');
    }

    public function load(ObjectManager $manager): void
    {
        $now = new \DateTime();

        for ($i = self::POOL_MIN; $i < self::POOL_MAX; $i++) {
            $dateCreated = $this->faker->dateTimeBetween('-1 year', 'now');
            $dateUpdated = $this->faker->dateTimeBetween($dateCreated, $now);

            $client = new Client();
            $client
                ->setQuantity((string) $this->faker->numberBetween(1, 1000))
                ->setCreatedAt($dateCreated)
                ->setUpdatedAt($dateUpdated)
                ->setStatus($this->faker->randomElement(['active', 'inactive']))
                ->setPrice((string) $this->faker->randomFloat(2, 10, 1000))
                ->setPriceUnit($this->faker->randomElement(['EUR', 'USD', 'GBP']));

            $manager->persist($client);

            $this->addReference(self::PREFIX . $i, $client);
        }

        $manager->flush();
    }
}
