<?php

namespace App\DataFixtures;
use App\Entity\History;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;


class HistoryFixtures extends Fixture  implements DependentFixtureInterface
{
    public const PREFIX = "history#";
    public const POOL_MIN = 0;
    public const POOL_MAX = 10;


    
    public function __construct()
    {
        $this->faker = Factory::create('fr_FR');
    }
    public function load(ObjectManager $manager): void
    {
        $now = new \DateTime();
        $prefixAction = ActionFixtures::PREFIX;
        $actionRefs = [];

        for ($i = ActionFixtures::POOL_MIN; $i < ActionFixtures::POOL_MAX; $i++) {
            $actionRefs[] = $prefixAction . $i;
        }

        for ($i = self::POOL_MIN; $i < self::POOL_MAX; $i++) {
            $dateCreated = $this->faker->dateTimeInInterval('-1 year', '+1 year');
            
            $action = $this->getReference($actionRefs[array_rand($actionRefs, 1)]);
            $history = new History();
            $history
                ->setName($this->faker->numerify('history-###'))
                ->setClient($client)
                ->setCreatedAt($dateCreated)
                
                
            ;
        // $product = new Product();
        // $manager->persist($product);

        $manager->flush();
    }
    public function getDependencies(): array
{
    return [
        ActionFixtures::class
    ];
}
}
