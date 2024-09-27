<?php

namespace App\Tests\Unit\Form;

use App\Entity\Invoice;
use App\Entity\MerchantOrder;
use App\Form\MerchantOrderType;
use Symfony\Component\Form\Test\TypeTestCase;

class MerchantOrderTypeTest extends TypeTestCase
{
    public function testSubmitValidData(): void
    {
        $order = new MerchantOrder(
            amount: 100,
            country: 'US',
            currency: 'USD'
        );
        $formData = [
            'amount' => $order->getAmount(),
            'country' => $order->getCountry(),
            'currency' => $order->getCurrency(),
            'id' => $order->getId(),
            'invoice' => [
                'paymentMethod' => 'METHOD 1',
                'description' => 'Lorem ipsum',
            ],
            'payer' => [
                'document' => '123456789',
                'firstName' => 'John',
                'lastName' => 'Doe',
                'email' => 'john.doe@example.com',
                'phone' => '123456789',
            ],
        ];

        $form = $this->factory->create(MerchantOrderType::class, $order);

        $expected = new Invoice(
            paymentMethod: 'METHOD 1',
            description: 'Lorem ipsum',
        );

        $form->submit($formData);

        $this->assertTrue($form->isSynchronized());
        $this->assertEquals($expected->getPaymentMethod(), $order->getInvoice()->getPaymentMethod());
        $this->assertEquals($expected->getDescription(), $order->getInvoice()->getDescription());
    }
}
