<?php

namespace App\DataFixtures;

use App\Faker\Factory\MerchantOrderFactory;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class MerchantOrderFixtures extends Fixture
{
    public function __construct(private readonly MerchantOrderFactory $orderFactory)
    {
    }

    public function load(ObjectManager $manager): void
    {
        $factory = $this->orderFactory;

        for ($i = 0; $i < 10; ++$i) {
            $order = $factory();

            $this->addReference('merchant_order_' . $i, $order);

            $manager->persist($order);
        }

        $manager->flush();
    }
}
