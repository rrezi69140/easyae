<?php

namespace App\DataFixtures;

use App\Entity\Fonction;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class FonctionFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $fonction = new Fonction();

        $fonction->setName("NameTest");
        $fonction->setType("TypeTest");
        $fonction->setCreatedAt(new \DateTime(""));
        $fonction->setUpdatedAt(new \DateTime(""));
        $fonction->setStatus("on");
 
        $manager->persist($fonction);
        $manager->flush();
    }
}
