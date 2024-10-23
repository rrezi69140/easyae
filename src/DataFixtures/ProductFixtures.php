<?php

namespace App\DataFixtures;

use App\Entity\Product;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class ProductFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        for ($i = 0; $i < 20; ++$i) {
            $product = new Product();
            $product
                ->setQuantity(50 + $i)
                ->setCreatedAt(new \DateTime())
                ->setUpdatedAt(new \DateTime())
                ->setStatus("Disponible $i")
                ->setPrice(10.2 + $i)
                ->setPriceUnit(20 + $i);
            $manager->persist($product);
        }

        $manager->flush();
    }
}
