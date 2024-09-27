<?php

namespace App\Tests\Unit\Entity;

use App\Entity\Invoice;
use App\Entity\MerchantOrder;
use PHPUnit\Framework\TestCase;
use Ramsey\Uuid\Uuid;

class MerchantOrderTest extends TestCase
{
    public function testConstruct(): void
    {
        $id = Uuid::uuid4();
        $currency = 'EUR';
        $country = 'FR';
        $amount = 100;
        $invoice = $this->createMock(Invoice::class);

        $merchantOrder = new MerchantOrder(
            amount: $amount,
            country: $country,
            currency: $currency,
            id: $id,
            invoice: $invoice,
        );

        $this->assertSame($amount, $merchantOrder->getAmount());
        $this->assertSame($currency, $merchantOrder->getCurrency());
        $this->assertSame($country, $merchantOrder->getCountry());
        $this->assertSame($invoice, $merchantOrder->getInvoice());
        $this->assertSame($id, $merchantOrder->getId());
    }

    public function testSetMethods(): void
    {
        $id = Uuid::uuid4();
        $currency = 'EUR';
        $country = 'FR';
        $amount = 100;
        $invoice = $this->createMock(Invoice::class);

        $merchantOrder = new MerchantOrder(id: $id);
        $merchantOrder->setAmount($amount);
        $merchantOrder->setCurrency($currency);
        $merchantOrder->setCountry($country);
        $merchantOrder->setInvoice($invoice);

        $this->assertSame($amount, $merchantOrder->getAmount());
        $this->assertSame($currency, $merchantOrder->getCurrency());
        $this->assertSame($country, $merchantOrder->getCountry());
        $this->assertSame($invoice, $merchantOrder->getInvoice());
        $this->assertSame($id, $merchantOrder->getId());
    }
}
