<?php

namespace App\Tests\Unit\Entity;

use App\Entity\Callback;
use App\Entity\Invoice;
use App\Entity\MerchantOrder;
use App\Enum\InvoiceStatusEnum;
use DateTimeImmutable;
use PHPUnit\Framework\TestCase;
use Ramsey\Uuid\Uuid;

class InvoiceTest extends TestCase
{
    public function testConstruct(): void
    {
        $id = Uuid::uuid4();
        $paymentMethod = 'paymentMethod';
        $request = json_encode(['dummy' => 'value'], JSON_THROW_ON_ERROR);
        $response = json_encode(['response' => 'value'], JSON_THROW_ON_ERROR);
        $expirationDate = new DateTimeImmutable('+1 day');
        $status = InvoiceStatusEnum::SUCCESSFUL;
        $notificationUrl = 'https://example.com';
        $order = $this->createMock(MerchantOrder::class);
        $description = 'Lorem ipsum';
        $callback = $this->createMock(Callback::class);

        $invoice = new Invoice(
            paymentMethod: $paymentMethod,
            request: $request,
            response: $response,
            expirationDate: $expirationDate,
            status: $status,
            notificationUrl: $notificationUrl,
            order: $order,
            id: $id,
            description: $description,
        );
        $invoice->addCallback($callback);

        $this->assertSame($id, $invoice->getId());
        $this->assertSame($paymentMethod, $invoice->getPaymentMethod());
        $this->assertSame($request, $invoice->getRequest());
        $this->assertSame($response, $invoice->getResponse());
        $this->assertSame($expirationDate, $invoice->getExpirationDate());
        $this->assertSame($status, $invoice->getStatus());
        $this->assertSame($notificationUrl, $invoice->getNotificationUrl());
        $this->assertSame($order, $invoice->getOrder());
        $this->assertSame($description, $invoice->getDescription());
        $this->assertCount(1, $invoice->getCallbacks());
        $this->assertSame($callback, $invoice->getCallbacks()[0]);
    }

    public function testSetMethods(): void
    {
        $id = Uuid::uuid4();
        $paymentMethod = 'paymentMethod';
        $request = json_encode(['dummy' => 'value'], JSON_THROW_ON_ERROR);
        $response = json_encode(['response' => 'value'], JSON_THROW_ON_ERROR);
        $expirationDate = new DateTimeImmutable('+1 day');
        $status = InvoiceStatusEnum::SUCCESSFUL;
        $notificationUrl = 'https://example.com';
        $order = $this->createMock(MerchantOrder::class);
        $description = 'Lorem ipsum';
        $callback = $this->createMock(Callback::class);

        $invoice = new Invoice(id: $id);
        $invoice->setPaymentMethod($paymentMethod);
        $invoice->setRequest($request);
        $invoice->setResponse($response);
        $invoice->setExpirationDate($expirationDate);
        $invoice->setStatus($status);
        $invoice->setNotificationUrl($notificationUrl);
        $invoice->setOrder($order);
        $invoice->setDescription($description);

        $invoice->addCallback($callback);

        $this->assertSame($id, $invoice->getId());
        $this->assertSame($paymentMethod, $invoice->getPaymentMethod());
        $this->assertSame($request, $invoice->getRequest());
        $this->assertSame($response, $invoice->getResponse());
        $this->assertSame($expirationDate, $invoice->getExpirationDate());
        $this->assertSame($status, $invoice->getStatus());
        $this->assertSame($notificationUrl, $invoice->getNotificationUrl());
        $this->assertSame($order, $invoice->getOrder());
        $this->assertSame($description, $invoice->getDescription());
        $this->assertCount(1, $invoice->getCallbacks());
        $this->assertSame($callback, $invoice->getCallbacks()[0]);
    }

    public function testRemoveCallbackMethod(): void
    {
        $id = Uuid::uuid4();
        $callback = $this->createMock(Callback::class);

        $invoice = new Invoice(id: $id);
        $invoice->addCallback($callback);
        $this->assertCount(1, $invoice->getCallbacks());
        $this->assertSame($callback, $invoice->getCallbacks()[0]);

        $invoice->removeCallback($callback);
        $this->assertCount(0, $invoice->getCallbacks());

        $this->assertSame($id, $invoice->getId());
    }

    public function testToStringMethod(): void
    {
        $id = Uuid::uuid4();

        $invoice = new Invoice(id: $id);

        $this->assertSame($id->toString(), $invoice->__toString());
    }
}
