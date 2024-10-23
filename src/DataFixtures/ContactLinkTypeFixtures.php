<?php

namespace App\DataFixtures;

use App\Entity\ContactLinkType;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class ContactLinkTypeFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        for ($i = 0; $i < 20; $i++) {
            $contactLinkType = new ContactLinkType();
            $contactLinkType
                ->setName("ContactLinkType #".$i)
                ->setCreatedAt(new \DateTime())
                ->setUpdatedAt(new \DateTime())
                ->setStatus('on')
            ;
//             $contactLinkType->setCreatedAt(new \DateTime());
            $manager->persist($contactLinkType);
        }

        $manager->flush();
    }
}
