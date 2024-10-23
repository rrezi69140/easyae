<?php

namespace App\DataFixtures;

use Faker\Factory;
use Faker\Generator;
use App\Entity\Contact;
// use App\Entity\ContactLink;
// use App\Entity\Fonction;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class ContactFixtures extends Fixture
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

        // $fonction = new Fonction();
        // $fonction->setNom("Compte #1");
        // $manager->persist($fonction);

        
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
            // $contact->setCreatedAt(new \DateTime());
            $manager->persist($contact);
            $this->addReference(self::PREFIX . $i, $contact);
        }


        // $link = new ContactLink();
        // $link->setContact($contact);
        // $manager->persist($link);

        $manager->flush();
    }
}
