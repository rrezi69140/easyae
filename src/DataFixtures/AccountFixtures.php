<?php

namespace App\DataFixtures;

use App\Entity\Account;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

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
        // $account->setCreatedAt(new \DateTime());
        $manager->persist($account);

        $manager->flush();
    }
}
