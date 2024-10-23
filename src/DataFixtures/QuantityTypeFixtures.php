<?php

namespace App\DataFixtures;

use App\Entity\QuantityType;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class QuantityTypeFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $types = ["kebab", "frites", "euros", "roubles", "indiens"];
        for ($i = 0; $i < count($types); $i++) {
            $quantityType = new QuantityType();
            $quantityType
                ->setName($types[$i])
                ->setCreatedAt(new \DateTime())
                ->setUpdatedAt(new \DateTime())
                ->setStatus('on');
            $manager->persist($quantityType);

            $manager->flush();
        }
    }
}
