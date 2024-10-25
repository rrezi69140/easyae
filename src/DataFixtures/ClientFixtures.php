<?php

namespace App\DataFixtures;

use Faker\Factory;
use Faker\Generator;
use App\Entity\Client;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;

class ClientFixtures extends Fixture implements DependentFixtureInterface
{
    public const PREFIX = "client#";
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
        $facturationModelRefs = [];
        for ($i = FacturationModelFixtures::POOL_MIN; $i < FacturationModelFixtures::POOL_MAX; $i++) {
            $facturationModelRefs[] = FacturationModelFixtures::PREFIX . $i;
        }

        $contactRefs = [];
        for ($i = ContactFixtures::POOL_MIN; $i < ContactFixtures::POOL_MAX; $i++) {
            $contactRefs[] = ContactFixtures::PREFIX . $i;
        }

        $facturationModelCount = count($facturationModelRefs);
        $contactCount = count($contactRefs);

        for ($i = self::POOL_MIN; $i < self::POOL_MAX; $i++) {
            $client = new Client();
            $client->setName($this->faker->company);
            $dateCreated = $this->faker->dateTimeBetween('-2 years', 'now');
            $dateUpdated = $this->faker->dateTimeBetween($dateCreated, $now);

            $client->setCreatedAt($dateCreated);
            $client->setUpdatedAt($dateUpdated);

            $client->setStatus('on');

            if ($facturationModelCount > 0) {
                $facturationModel = $this->getReference($facturationModelRefs[$i % $facturationModelCount]);
                $client->setFacturationModel($facturationModel);
            }

            if ($contactCount > 0) {
                $contact = $this->getReference($contactRefs[$i % $contactCount]);
                $client->setContact($contact);
            }

            $manager->persist($client);
            $this->addReference(self::PREFIX . $i, $client);
        }

        $manager->flush();
    }

    public function getDependencies(): array
    {
        return [
            FacturationModelFixtures::class,
            ContactFixtures::class,
        ];
    }
}
