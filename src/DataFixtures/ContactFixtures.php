<?php

namespace App\DataFixtures;

use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Faker\Factory;
use Faker\Generator;
use App\Entity\Contact;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;

class ContactFixtures extends Fixture implements DependentFixtureInterface
{

    public const PREFIX = "contact#";
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

        $prefixContactLink = ContactLinkFixtures::PREFIX;
        $contactLinkRefs = [];
        for ($i = ContactLinkFixtures::POOL_MIN; $i < ContactLinkFixtures::POOL_MAX; $i++) {
            $contactLinkRefs[] = $prefixContactLink . $i;
        }

        $prefixFonction = FonctionFixtures::PREFIX;
        $fonctionRefs = [];
        for ($i = FonctionFixtures::POOL_MIN; $i < FonctionFixtures::POOL_MAX; $i++) {
            $fonctionRefs[] = $prefixFonction . $i;
        }

        for ($i = self::POOL_MIN; $i < self::POOL_MAX; $i++) {
            $dateCreated = $this->faker->dateTimeInInterval('-1 year', '+1 year');
            $dateUpdated = $this->faker->dateTimeBetween($dateCreated, $now);
            $contact = new Contact();
            $contact
                ->setName($this->faker->firstName(null))
                ->setCreatedAt($dateCreated)
                ->setUpdatedAt($dateUpdated)
                ->setStatus('on')
            ;
            $manager->persist($contact);
            $this->addReference(self::PREFIX . $i, $contact);
        }

        $manager->flush();
    }

    public function getDependencies(): array
    {
        return [
            ContactLinkFixtures::class,
            FonctionFixtures::class
        ];
    }
}