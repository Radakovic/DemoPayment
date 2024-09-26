<?php

namespace App\DataFixtures;

use App\Faker\Factory\InvoiceFactory;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

class InvoiceFixtures extends Fixture implements DependentFixtureInterface
{
    public function __construct(private readonly InvoiceFactory $invoiceFactory)
    {
    }

    public function load(ObjectManager $manager): void
    {
        $factory = $this->invoiceFactory;

        for ($i = 0; $i < 3; $i++) {
            $order = $this->getReference("merchant_order_$i");

            $invoice = $factory(
                order: $order,
            );
            $this->addReference("invoice_$i", $invoice);

            $manager->persist($invoice);
        }
        $manager->flush();
    }

    public function getDependencies(): array
    {
        return [MerchantOrderFixtures::class];
    }
}
