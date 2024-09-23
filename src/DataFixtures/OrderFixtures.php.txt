<?php

namespace App\DataFixtures;

use App\DataFixtures\Factory\OrderFactory;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class OrderFixtures extends Fixture
{
    public function __construct(private readonly OrderFactory $orderFactory)
    {
    }

    public function load(ObjectManager $manager): void
    {
        $factory = new OrderFactory();
        $manager->flush();
    }
}
