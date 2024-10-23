<?php

namespace App\DataFixtures;

use Faker\Factory;
use Faker\Generator;
use App\Entity\InfoType;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class InfoTypeFixtures extends Fixture
{
    
    public const PREFIX = "InfoType";
    public const POOL_MIN = 0;
    public const POOL_MAX = 25;
    private Generator $faker;

    public function __construct()
    {
        $this->faker = Factory::create('fr_FR');
    }
    public function load(ObjectManager $manager): void
    {
        $now = new \DateTime();
       
       for ($i = self::POOL_MIN; $i < self::POOL_MAX; $i++) {
        $dateCreated = $this->faker->dateTimeInInterval('-1 year', '+1 year');
        $dateUpdated = $this->faker->dateTimeBetween($dateCreated, $now);
        $infoType = new InfoType();
        $infoType->setName("InfoType  ".$this->faker->word());
        $infoType->setInfo("Personal Info ");
        $infoType->setCreatedAt($dateCreated);
        $infoType->setupdateAt($dateUpdated);
        $infoType->setStatus('on')
       ;
       $manager->persist($infoType);
       $this->addReference(self::PREFIX . $i, $infoType);
       }
    

        $manager->flush();
    }
}
