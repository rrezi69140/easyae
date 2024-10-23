<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use App\Entity\Client;
use DateTime;

class ClientFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        for ($i = 1; $i <= 10; $i++) {
            $client = new Client();
            
            $client->setType('Type' . $i);
            $client->setQuantityType('kg');
            $client->setQuantity((string) rand(1, 100));
            $client->setCreatedAt(new DateTime());
            $client->setUpdatedAt(new DateTime());
            $client->setStatus('active');
            $client->setPrice((string) rand(100, 1000));
            $client->setPriceUnit('EUR');

            $manager->persist($client);
        }

        $manager->flush();
    }
}
