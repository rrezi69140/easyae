<?php

namespace App\DataFixtures;

use App\Entity\ContactLink;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class ContactLinkFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        for ($i = 0; $i < 10; $i++) {
            $contactLink = new ContactLink();
            $contactLink->setValue('test_value');
            $contactLink->setType('test_type');
            $contactLink->setCreatedAt(new \DateTime());
            $contactLink->setUpdatedAt(new \DateTime());
            $contactLink->setStatus('test_status');
            $manager->persist($contactLink);
        }
        
        $manager->flush();
    }
}
