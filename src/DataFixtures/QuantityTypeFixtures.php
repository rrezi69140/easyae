<?php

namespace App\DataFixtures;

use App\Entity\QuantityType;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class QuantityTypeFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $quantityType = new QuantityType();
        $quantityType
            ->setName("Quantité 1")
            ->setCreatedAt(new \DateTime())
            ->setUpdatedAt(new \DateTime())
            ->setStatus('on')
        ;
        $manager->persist($quantityType);

        $manager->flush();
    }
}
