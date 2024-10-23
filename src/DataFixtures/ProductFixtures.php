<?php

namespace App\DataFixtures;

use App\Entity\Product;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;
use Faker\Generator;

class ProductFixtures extends Fixture
{

    public const PREFIX = "product#";
    public const PRODUCT_POOL_MIN = 0;
    public const PRODUCT_POOL_MAX = 20;

    private Generator $faker;
    public function __construct()
    {
        $this->faker = Factory::create('fr_FR');
    }
    public function load(ObjectManager $manager): void
    {
        $now = new \DateTime();

        for ($i = self::PRODUCT_POOL_MIN; $i < self::PRODUCT_POOL_MAX; ++$i) {
            $dateCreated = $this->faker->dateTimeInInterval('-1 year', '+1 year');
            $dateUpdated = $this->faker->dateTimeBetween($dateCreated, $now);
            $product = new Product();
            $statuses = ['OK', 'Failed', 'Pending'];
            $product
                ->setQuantity($this->faker->randomDigit())
                ->setCreatedAt($dateCreated)
                ->setUpdatedAt($dateUpdated)
                ->setStatus($statuses[rand(0, count($statuses)-1)])
                ->setPrice($this->faker->randomFloat(2, 10, 100))
                ->setPriceUnit($this->faker->randomFloat(2, 10, 100));
            $manager->persist($product);
            $this->addReference(self::PREFIX . $i, $product);
        }

        $manager->flush();
    }
}
