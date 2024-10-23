<?php

namespace App\DataFixtures;

use Faker\Factory;
use Faker\Generator;
use App\Entity\Client;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;

class ClientFixtures extends Fixture
{
    private Generator $faker;

    public function __construct()
    {
        // Initialisation de Faker en français
        $this->faker = Factory::create('fr_FR');
    }

    public function load(ObjectManager $manager): void
    {
        $now = new \DateTime();

        for ($i = 0; $i < 10; $i++) {
            $dateCreated = $this->faker->dateTimeBetween('-1 year', 'now');
            $dateUpdated = $this->faker->dateTimeBetween($dateCreated, $now);

            $client = new Client();
            $client
                ->setType($this->faker->randomElement(['Business', 'Individual'])) // Type aléatoire
                ->setQuantityType($this->faker->randomElement(['kg', 'liters', 'pieces'])) // Unité de quantité
                ->setQuantity((string) $this->faker->numberBetween(1, 1000)) // Quantité aléatoire
                ->setCreatedAt($dateCreated)
                ->setUpdatedAt($dateUpdated)
                ->setStatus($this->faker->randomElement(['active', 'inactive'])) // Statut aléatoire
                ->setPrice((string) $this->faker->randomFloat(2, 10, 1000)) // Prix aléatoire
                ->setPriceUnit($this->faker->randomElement(['EUR', 'USD', 'GBP'])); // Unité de prix

            $manager->persist($client);
        }

        $manager->flush();
    }
}

