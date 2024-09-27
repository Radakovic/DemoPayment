<?php

namespace App\Faker\Factory;

use App\DataFixtures\Factory\AbstractFactory;
use App\Entity\Callback;
use App\Entity\Invoice;
use App\Entity\MerchantOrder;
use App\Enum\InvoiceStatusEnum;
use Ramsey\Uuid\Uuid;
use Ramsey\Uuid\UuidInterface;

class CallbackFactory extends AbstractFactory
{
    public function getEntity(): string
    {
        return Callback::class;
    }

    public function __invoke(
        ?string $response = null,
        ?string $request = null,
        ?Invoice $invoice = null,
        ?UuidInterface $id = null,
    ): Callback {
        $id = $id ?? Uuid::uuid4();
        $invoice = $invoice ?? $this->createEntityIfNotExists(Invoice::class);
        $invoice->setStatus(InvoiceStatusEnum::SUCCESSFUL);
        $order = $this->createEntityIfNotExists(MerchantOrder::class);
        $invoice->setOrder($order);
        $order->setInvoice($invoice);

        $request = $request ?? $this->createJsonRequest($invoice->getOrder());
        $response = $response ?? $this->createJsonResponse($invoice);

        return new Callback(
            response: $response,
            request: $request,
            invoice: $invoice,
            id: $id
        );
    }

    private function createJsonResponse(Invoice $invoice): string
    {
        $responses = $this->callbackResponses();

        $responseCode = match ($invoice->getStatus()) {
            InvoiceStatusEnum::SUCCESSFUL => 2000,
            InvoiceStatusEnum::ERROR => $this->faker->randomElement([1003, 1004]),
            InvoiceStatusEnum::EXPIRED => 1006,
            InvoiceStatusEnum::REJECTED => 1005,
        };

        return json_encode($responses[$responseCode], JSON_THROW_ON_ERROR);
    }

    private function createJsonRequest(MerchantOrder $merchantOrder): string
    {
        $request = [
            'headers' => [
                'Content-Type' => 'application/json',
                'X-signature' => $this->signData($merchantOrder->getInvoice()?->getRequest()),
            ],
            'body' => [
                "merchant_order_id" => $merchantOrder->getId(),
                "amount" => $merchantOrder->getAmount() / 100,
                "currency" => $merchantOrder->getCurrency(),
                "status" => $merchantOrder->getInvoice()?->getStatus(),
                "timestamp" => time(),
            ]
        ];

        return json_encode($request, JSON_THROW_ON_ERROR);
    }

    private function callbackResponses(): array
    {
        return [
            '1001' => [
                'status_code' => 400,
                'error' => [
                    'error_code' => 1001,
                    'error' => 'Merchant order does not exists',
                ],
                'redirect_url' => '/error/payment/1001',
            ],
            '1002' => [
                'status_code' => 400,
                'error' => [
                    'error_code' => 1002,
                    'error' => 'Invoice is not create for merchant order',
                ],
                'redirect_url' => '/error/payment/1002',
            ],
            '1003' => [
                'status_code' => 400,
                'error' => [
                    'error_code' => 1003,
                    'error' => 'Invalid signature',
                ],
                'redirect_url' => '/error/payment/1003',
            ],
            '1004' => [
                'status_code' => 400,
                'error' => [
                    'error_code' => 1004,
                    'error' => 'Invoice already payed',
                ],
                'redirect_url' => '/error/payment/1004',
            ],
            '1005' => [
                'status_code' => 400,
                'error' => [
                    'error_code' => 1005,
                    'error' => 'Amount is not set',
                ],
                'redirect_url' => '/error/payment/1005',
            ],
            '1006' => [
                'status_code' => 400,
                'error' => [
                    'error_code' => 1006,
                    'error' => 'Expiration date is passed',
                ],
                'redirect_url' => '/error/payment/1006',
            ],
            '2000' => [
                'status_code' => 201,
                'success' => [
                    'success_code' => 2000,
                ],
                'redirect_url' => '/success/payment',
            ],
        ];
    }

    public function signData(string $body): string
    {
        return base64_encode(hash_hmac('sha256', $body, 'secret_key'));
    }
}
