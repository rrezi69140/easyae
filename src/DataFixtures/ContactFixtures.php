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

        for ($i = 0; $i < 5; $i++) {
            $dateCreated = $this->faker->dateTimeInInterval('-1 year', '+1 year');
            $dateUpdated = $this->faker->dateTimeBetween($dateCreated, $now);
            $links = $this->getReference($contactLinkRefs[array_rand($contactLinkRefs, 1)]);
            $fonction = $this->getReference($fonctionRefs[array_rand($fonctionRefs, 1)]);

            $contact = new Contact();
            $contact
                ->setName($this->faker->firstName(null))
                ->addLink($links)
                ->addFonction($fonction)
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