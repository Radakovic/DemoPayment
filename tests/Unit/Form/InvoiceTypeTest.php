<?php

namespace App\Tests\Unit\Form;

use App\Entity\Invoice;
use App\Form\InvoiceType;
use Ramsey\Uuid\Uuid;
use Symfony\Component\Form\Test\TypeTestCase;

class InvoiceTypeTest extends TypeTestCase
{
    public function testSubmitValidData(): void
    {
        $formData = [
            'paymentMethod' => 'METHOD 1',
            'description' => 'Lorem ipsum',
        ];

        $id = Uuid::uuid4();
        $model = new Invoice(id: $id);

        $form = $this->factory->create(InvoiceType::class, $model);

        $expected = new Invoice(
            paymentMethod: $formData['paymentMethod'],
            id: $id,
            description: $formData['description']
        );

        $form->submit($formData);

        $this->assertTrue($form->isSynchronized());
        $this->assertEquals($expected, $model);
    }
}
