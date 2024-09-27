<?php

namespace App\DataFixtures;

use App\Entity\Invoice;
use App\Enum\InvoiceStatusEnum;
use App\Faker\Factory\CallbackFactory;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

class CallbackFixtures extends Fixture implements DependentFixtureInterface
{
    public function __construct(private readonly CallbackFactory $callbackFactory)
    {
    }

    public function load(ObjectManager $manager)
    {
        $factory = $this->callbackFactory;
        $invoices = $manager->getRepository(Invoice::class)->findAll();

        foreach ($invoices as $invoice) {
            if (!$this->shouldCreateCallbacks($invoice->getStatus())) {
                continue;
            }
            $callback = $factory(invoice: $invoice, order: $invoice->getOrder());
            $invoice->addCallback($callback);

            $manager->persist($callback);
        }

        $manager->flush();
    }

    public function getDependencies(): array
    {
        return [
            MerchantOrderFixtures::class,
            InvoiceFixtures::class
        ];
    }

    private function shouldCreateCallbacks(InvoiceStatusEnum $status): bool
    {
        return $status !== InvoiceStatusEnum::CREATED;
    }
}
