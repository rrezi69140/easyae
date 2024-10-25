<?php

namespace App\DataFixtures;

use App\Entity\Info;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Generator;
use Faker\Factory;

class InfoFixtures extends Fixture
{
    public const PREFIX = "info#";
    public const POOL_MIN = 0;
    public const POOL_MAX = 10;
    private Generator $faker;
    public function __construct()
    {
        $this->faker = Factory::create("fr_FR");
    }
    public function load(ObjectManager $manager): void
    {
        $now = new \DateTime();

        for ($i = self::POOL_MIN; $i < self::POOL_MAX; $i++) {
            $dateCreated = $this->faker->dateTimeInInterval('-1 year', '+1 year');
            $dateUpdated = $this->faker->dateTimeBetween($dateCreated, $now);
            $info = new Info();
            $info
                ->setAnonymous($this->faker->boolean(20))
                ->setInfo($this->faker->numerify('info-###'))
                ->setCreatedAt($dateCreated)
                ->setUpdatedAt($dateUpdated)
                ->setStatus($this->faker->boolean() ? "on" : "off");
            $manager->persist($info);
            $this->addReference(self::PREFIX . $i, $info);
        }

        $manager->flush();
    }
}
