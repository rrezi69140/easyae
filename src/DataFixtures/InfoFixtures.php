<?php

namespace App\DataFixtures;

use App\Entity\Info;
use App\Entity\InfoType;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Persistence\ObjectManager;
use Faker\Generator;
use Faker\Factory;

class InfoFixtures extends Fixture implements DependentFixtureInterface
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
        $adminUser = $this->getReference(UserFixtures::ADMIN_REF);
        $now = new \DateTime();
        $prefixInfo = InfoTypeFixtures::PREFIX;

        $infoTypeRefs = [];
        for ($i = InfoTypeFixtures::POOL_MIN; $i < InfoTypeFixtures::POOL_MAX; $i++) {
            $infoTypeRefs[] = $prefixInfo . $i;
        }

        for ($i = self::POOL_MIN; $i < self::POOL_MAX; $i++) {
            $dateCreated = $this->faker->dateTimeInInterval('-1 year', '+1 year');
            $dateUpdated = $this->faker->dateTimeBetween($dateCreated, $now);
            $type = $this->getReference($infoTypeRefs[array_rand($infoTypeRefs, 1)]);
            $info = new Info();
            $info
                ->setAnonymous($this->faker->boolean(20))
                ->setInfo($this->faker->numerify('info-###'))
                ->setType($type)
                ->setCreatedAt($dateCreated)
                ->setUpdatedAt($dateUpdated)
                ->setStatus($this->faker->boolean() ? "on" : "off")
                ->setCreatedBy($adminUser->getId())
                ->setUpdatedBy($adminUser->getId())
                ;
            $manager->persist($info);
            $this->addReference(self::PREFIX . $i, $info);
        }

        $manager->flush();
    }
    
    public function getDependencies(): array
    {
        return [
            UserFixtures::class,
            InfoTypeFixtures::class,
        ];
    }
}
