<?php

namespace App\Controller\Payment;

use App\Entity\Callback;
use App\Entity\Invoice;
use App\Entity\MerchantOrder;
use App\Enum\InvoiceStatusEnum;
use App\Service\PSPServiceInterface;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class SimulatePaymentController extends AbstractController
{
    public function __construct(
        private readonly PSPServiceInterface $pspService,
        private readonly EntityManagerInterface $entityManager,
    ) {
    }

    #[Route(path: '/simulation/order/{id}', name: 'simulation', methods: ['GET'])]
    public function simulation(MerchantOrder $merchantOrder): Response
    {
        $request = $this->createRequest($merchantOrder);

        $response = $this->pspService->postCallback($request);

        $callback = new Callback(
            response: json_encode($response, JSON_THROW_ON_ERROR),
            request: json_encode($request, JSON_THROW_ON_ERROR) ,
            invoice: $merchantOrder->getInvoice(),
        );
        $this->entityManager->persist($callback);

        $this->updateInvoiceStatus(merchantOrder: $merchantOrder, response: $response);

        $this->entityManager->flush();
        return $this->redirect($response['redirect_url']);
    }

    #[Route(path: '/success/payment', name: 'success_payment', methods: ['GET'])]
    public function successPayment(): Response
    {
        return $this->render('invoice/success.html.twig');
    }
    #[Route(path: '/error/payment/{errorCode}', name: 'error_payment', methods: ['GET'])]
    public function errorPayment(int $errorCode): Response
    {
        $message = match ($errorCode) {
            1001 => 'Merchant order does not exists',
            1002 => 'Invoice is not create for merchant order',
            1003 => 'Invalid signature',
            1004 => 'Invoice already payed',
            1005 => 'Amount is not set',
            1006 => 'Expiration date is passed',
        };
        return $this->render('invoice/error.html.twig', ['errorCode' => $errorCode, 'message' => $message]);
    }

    private function createRequest(MerchantOrder $merchantOrder): array
    {
        $invoice = $merchantOrder->getInvoice();
        return [
            'headers' => [
                'Content-Type' => 'application/json',
                'X-signature' => $this->pspService->signData($invoice?->getRequest()),
            ],
            'body' => [
                "merchant_order_id" => $merchantOrder->getId(),
                "amount" => $merchantOrder->getAmount() / 100,
                "currency" => $merchantOrder->getCurrency(),
                "status" => $merchantOrder->getInvoice()?->getStatus(),
                "timestamp" => time(),
            ]
        ];
    }

    private function updateInvoiceStatus(MerchantOrder $merchantOrder, array $response): void
    {
        $invoice = $merchantOrder->getInvoice();
        assert($invoice instanceof Invoice);

        if ($response['status_code'] === 201) {
            $invoice->setStatus(InvoiceStatusEnum::SUCCESSFUL);
            return;
        }

        if ($response['status_code'] === 400 && $response['error']['error_code'] === 1005) {
            $invoice->setStatus(InvoiceStatusEnum::REJECTED);
            return;
        }

        if ($response['status_code'] === 400 && $response['error']['error_code'] === 1005) {
            $invoice->setStatus(InvoiceStatusEnum::EXPIRED);
            return;
        }

        $invoice->setStatus(InvoiceStatusEnum::ERROR);
        $this->entityManager->persist($invoice);
    }
}
