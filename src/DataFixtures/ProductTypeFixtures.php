<?php

namespace App\DataFixtures;

use App\Entity\ProductType;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;

class ProductTypeFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        for ($i = 0; $i < 20; ++$i) {
            $productType = new ProductType();
            $productType
                ->setName("Type produit #$i")
                ->setPrice(price: 10.3 + $i)
                ->setCreatedAt(new \DateTime())
                ->setUpdatedAt(new \DateTime())
                ->setStatus('on')
            ;
            $manager->persist($productType);
        }
        $manager->flush();

    }
}
