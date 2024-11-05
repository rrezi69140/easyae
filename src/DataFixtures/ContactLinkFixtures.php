<?php

namespace App\DataFixtures;

use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Faker\Factory;
use Faker\Generator;
use App\Entity\ContactLink;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class ContactLinkFixtures extends Fixture implements DependentFixtureInterface
{
    public const PREFIX = "contactLink#";
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

        $prefixContact = ContactFixtures::PREFIX;
        $contactRefs = [];
        for ($i = ContactFixtures::POOL_MIN; $i < ContactFixtures::POOL_MAX; $i++) {
            $contactRefs[] = $prefixContact . $i;
        }

        $prefixContactLinkType = ContactLinkTypeFixtures::PREFIX;
        $contactLinkTypeRefs = [];
        for ($i = ContactLinkTypeFixtures::POOL_MIN; $i < ContactLinkTypeFixtures::POOL_MAX; $i++) {
            $contactLinkTypeRefs[] = $prefixContactLinkType . $i;
        }
        for ($i = self::POOL_MIN; $i < self::POOL_MAX; $i++) {
            $dateCreated = $this->faker->dateTimeInInterval('-1 year', '+1 year');
            $dateUpdated = $this->faker->dateTimeBetween($dateCreated, $now);
            $contact = $this->getReference($contactRefs[array_rand($contactRefs, 1)]);
            $contactLinkType = $this->getReference($contactLinkTypeRefs[array_rand($contactLinkTypeRefs, 1)]);

            $contactLink = new ContactLink();
            $contactLink
                ->setContact($contact)
                ->setValue($this->faker->numerify('contact-link-###'))
                ->setContactLinkType($contactLinkType)
                ->setCreatedAt($dateCreated)
                ->setUpdatedAt($dateUpdated)
                ->setStatus('on')
            ;
            $manager->persist($contactLink);
            $this->addReference( self::PREFIX . $i, $contactLink);
        }
        $manager->flush();
    }

    public function getDependencies(): array
    {
        return [
            ContactFixtures::class,
            ContactLinkTypeFixtures::class,
        ];
    }
}
