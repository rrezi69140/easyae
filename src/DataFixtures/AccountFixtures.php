<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use App\Entity\Account;

class AccountFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $account = new Account();
        $account
            ->setName("Compte #1")
            ->setCreatedAt(new \DateTime())
            ->setUpdatedAt(new \DateTime())
            ->setStatus('on')
        ;
        $manager->persist($account);

        $manager->flush();
    }
}
