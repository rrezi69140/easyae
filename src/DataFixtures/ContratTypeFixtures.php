<?php

namespace App\DataFixtures;

use App\Entity\ContratType;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Datetime;

class ContratTypeFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {

        for ($i = 0; $i < 20; $i++) {
            $contratType = new ContratType();

            $contratType
                ->setName("contrat type" . $i)
                ->setUpdatedAt(new DateTime())
                ->setCreatedAt(new DateTime())
                ->setStatus("on")
            ;

            $manager->persist($contratType);
        }

        $manager->flush();
    }
}
