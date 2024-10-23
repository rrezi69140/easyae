<?php

namespace App\DataFixtures;

use Faker\Factory;
use Faker\Generator;
use App\Entity\ProductType;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;

class ProductTypeFixtures extends Fixture
{
    private Generator $faker;
    public function __construct()
    {
        $this->faker = Factory::create('fr_FR');
    }
    public function load(ObjectManager $manager): void
    {
        $now = new \DateTime();

        for ($i = 0; $i < 20; ++$i) {
            $dateCreated = $this->faker->dateTimeInInterval('-1 year', '+1 year');
            $dateUpdated = $this->faker->dateTimeBetween($dateCreated, $now);
            
            $productType = new ProductType();
            $productType
                ->setName($this->faker->numerify('product type-###'))
                ->setPrice(price: 10.3 + $i)
                ->setCreatedAt($dateCreated)
                ->setUpdatedAt($dateUpdated)
                ->setStatus('on')
            ;
            $manager->persist($productType);
        }
        $manager->flush();

    }
}
