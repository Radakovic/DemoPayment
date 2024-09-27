<?php

namespace App\Tests\Unit\Entity;

use App\Entity\Callback;
use App\Entity\Invoice;
use PHPUnit\Framework\TestCase;
use Ramsey\Uuid\Uuid;

class CallbackTest extends TestCase
{
    public function testConstruct(): void
    {
        $response = 'json response';
        $request = 'json request';
        $invoice = $this->createMock(Invoice::class);
        $id = Uuid::uuid4();
        $callback = new Callback(
            response: $response,
            request: $request,
            invoice: $invoice,
            id: $id
        );

        $this->assertEquals($response, $callback->getResponse());
        $this->assertEquals($request, $callback->getRequest());
        $this->assertEquals($id, $callback->getId());
        $this->assertEquals($invoice, $callback->getInvoice());
    }

    public function testSetMethods(): void
    {
        $response = 'json response';
        $request = 'json request';
        $invoice = $this->createMock(Invoice::class);
        $id = Uuid::uuid4();

        $callback = new Callback(id: $id);
        $callback->setResponse($response);
        $callback->setRequest($request);
        $callback->setInvoice($invoice);


        $this->assertEquals($response, $callback->getResponse());
        $this->assertEquals($request, $callback->getRequest());
        $this->assertEquals($id, $callback->getId());
        $this->assertEquals($invoice, $callback->getInvoice());
    }

    public function testToStringMethod(): void
    {
        $id = Uuid::uuid4();

        $callback = new Callback(id: $id);

        $this->assertEquals($id->toString(), $callback->__toString());
    }
}
