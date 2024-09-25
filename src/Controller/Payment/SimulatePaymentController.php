<?php

namespace App\Controller\Payment;

use App\Entity\MerchantOrder;
use App\Service\PSPServiceInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class SimulatePaymentController extends AbstractController
{
    public function __construct(
        private readonly PSPServiceInterface     $pspService,
    ) {
    }

    #[Route(path: '/simulation/order/{id}', name: 'simulation', methods: ['GET'])]
    public function simulation(MerchantOrder $merchantOrder): Response
    {
        $invoice = $merchantOrder->getInvoice();
        $request = [
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
        $response = $this->pspService->postCallback($request);
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
        };
        return $this->render('invoice/error.html.twig', ['errorCode' => $errorCode, 'message' => $message]);
    }
}
