<?php

namespace App\DataFixtures;

use App\Entity\ContactLinkType;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;
use Faker\Generator;

class ContactLinkTypeFixtures extends Fixture
{
    public const PREFIX = "contactLinkType#";
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
            $dateCreated = $this->faker->dateTimeInInterval('-1 year', '+1 year');
            $dateUpdated = $this->faker->dateTimeBetween($dateCreated, $now);
            $contactLinkType = new ContactLinkType();
            $contactLinkType
                ->setName($this->faker->numerify('contact-link-type-###'))
                ->setCreatedAt($dateCreated)
                ->setUpdatedAt($dateUpdated)
                ->setStatus('on')
            ;
            $manager->persist($contactLinkType);
            $this->addReference(self::PREFIX . $i, $contactLinkType);
        }

        $manager->flush();
    }
}
