<?php

namespace App\Controller\Payment;

use App\Entity\Invoice;
use App\Entity\MerchantOrder;
use App\Enum\InvoiceStatusEnum;
use App\Form\MerchantOrderType;
use App\Repository\MerchantOrderRepository;
use App\Service\PSPServiceInterface;
use DateTimeImmutable;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class MerchantOrderController extends AbstractController
{
    public function __construct(
        private readonly MerchantOrderRepository $orderRepository,
        private readonly PSPServiceInterface     $pspService,
        private readonly EntityManagerInterface  $entityManager,
    ) {
    }

    #[Route(path: '/', name: 'merchant_order_index', methods: ['GET'])]
    public function index(): Response
    {
        // Here should be logic for finding orders, not like in this example
        $merchantOrders = $this->orderRepository->findBy([
            'invoice' => null
        ]);

        return $this->render('/invoice/index.html.twig', [
            'orders' => $merchantOrders,
        ]);
    }

    #[Route(path: '/orders/{id}', name: 'create_invoice', methods: ['GET', 'POST'])]
    public function create(MerchantOrder $merchantOrder, Request $request): Response
    {
        $form = $this->createForm(MerchantOrderType::class, $merchantOrder);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $order = $form->getData();
            $invoice = $order->getInvoice();
            $payer = $form->get('payer')->getData();
            assert($order instanceof MerchantOrder);
            assert($invoice instanceof Invoice);

            [$requestBody, $notificationUrl] = $this->prepareApiRequest(
                order: $order,
                invoice: $invoice,
                payer: $payer,
                request: $request
            );

            $response = $this->pspService->postInvoice(requestBody: $requestBody);

            $jsonResponse = json_encode($response, JSON_THROW_ON_ERROR);
            $invoiceStatus = $response['statusCode'] === 201 ? InvoiceStatusEnum::CREATED : InvoiceStatusEnum::ERROR;
            //$redirectUrl = $response['body']['redirectUrl'];

            $invoice->setExpirationDate(new DateTimeImmutable("+1 day"));
            $invoice->setRequest($requestBody);
            $invoice->setResponse($jsonResponse);
            $invoice->setOrder($order);
            $invoice->setNotificationUrl($notificationUrl);
            $invoice->setStatus($invoiceStatus);

            $this->entityManager->persist($invoice);
            $this->entityManager->flush();

            return $this->redirectToRoute('show_invoice', ['id' => $invoice->getId()]);
        }

        return $this->render('/invoice/create.html.twig', [
            'form' => $form,
        ]);
    }

    #[Route('/invoice/{id}', name: 'show_invoice', methods: ['GET'])]
    public function show(Invoice $invoice): Response
    {
        $invoiceData = json_decode($invoice->getResponse(), true);
        return $this->render(
            'invoice/show.html.twig',
            [
                'invoice' => $invoiceData['body'],
                'invoiceId' => $invoice->getId(),
                'notificationUrl' => $invoice->getNotificationUrl(),
            ]
        );
    }

    private function prepareApiRequest(MerchantOrder $order, Invoice $invoice, $payer, Request $request): array
    {
        $notificationUrl = sprintf('notification/invoice/%s', $invoice->getId()->toString());
        $requestBody = [
            "merchant_order_id" => $order->getId()->toString(),
            "amount" => $order->getAmount() / 100,
            "country" => $order->getCountry(),
            "currency" => $order->getCurrency(),
            "payer" => [
                "document" => $payer['document'],
                "first_name" => $payer['firstName'],
                "last_name" => $payer['lastName'],
                "phone" => $payer['phone'],
                "email" => $payer['email'],
            ],
            "payment_method" => $order->getInvoice()?->getPaymentMethod(),
            "description" => $order->getInvoice()?->getDescription(),
            "client_ip" => $request->getClientIp(),
            "notification_url" => $notificationUrl,
        ];

        return [
            json_encode($requestBody, JSON_THROW_ON_ERROR),
            $notificationUrl
        ];
    }
}
