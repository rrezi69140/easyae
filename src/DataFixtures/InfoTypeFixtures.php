<?php

namespace App\DataFixtures;


use App\Entity\InfoType;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class InfoTypeFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
       
       
       for ($i = 0; $i < 20; $i++) {
        $infoType = new InfoType();
       $infoType->setName("Personal $i");
       $infoType->setInfo("Personal Info ");
       $infoType->setCreatedAt(new \DateTime());
       $infoType->setupdateAt(new \DateTime());
       $infoType->setStatus('on')
       ;
       $manager->persist($infoType);
       }
    

        $manager->flush();
    }
}
