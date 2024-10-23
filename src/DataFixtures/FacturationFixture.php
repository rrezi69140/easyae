<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use App\Entity\Facturation;

class FacturationFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $facturation = new Facturation();
        $facturation->setName("test")
                    ->setStatus("ratio")
                    ->setCreatedAt(new \DateTime())
                    ->setUpdatedAt(new \DateTime())
        ;

        $manager->persist($facturation);

        $manager->flush();
    }
}
	