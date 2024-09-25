<?php

namespace App\Controller\Payment;

use App\Entity\Invoice;
use App\Entity\MerchantOrder;
use App\Enum\InvoiceStatusEnum;
use App\Form\MerchantOrderType;
use App\Model\InvoiceResponseModel;
use App\Repository\MerchantOrderRepository;
use App\Service\PSPServiceInterface;
use DateTimeImmutable;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;

class InvoiceController extends AbstractController
{
    public function __construct(
        private readonly MerchantOrderRepository $orderRepository,
        private readonly PSPServiceInterface     $pspService,
        private readonly EntityManagerInterface $entityManager,
    ) {
    }

    #[Route('/', name: 'create_invoice', methods: ['GET', 'POST'])]
    public function create(Request $request): Response
    {
        $orders = $this->orderRepository->findby([
            'invoice' => null
        ]);
        $order = $orders[0];
        assert($order instanceof MerchantOrder);

        $form = $this->createForm(MerchantOrderType::class, $order);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $orderForm = $form->getData();
            $invoice = $orderForm->getInvoice();
            assert($orderForm instanceof MerchantOrder);
            assert($invoice instanceof Invoice);
            $payer = $form->get('payer')->getData();

            $notificationUrl = sprintf('notification/invoice/%s', $invoice->getId()->toString());
            $requestBody = [
                "merchant_order_id" => $orderForm->getId()->toString(),
                "amount" => $orderForm->getAmount() / 100,
                "country" => $orderForm->getCountry(),
                "currency" => $orderForm->getCurrency(),
                "payer" => [
                    "document" => $payer['document'],
                    "first_name" => $payer['firstName'],
                    "last_name" => $payer['lastName'],
                    "phone" => $payer['phone'],
                    "email" => $payer['email'],
                ],
                "payment_method" => $orderForm->getInvoice()?->getPaymentMethod(),
                "description" => $orderForm->getInvoice()?->getDescription(),
                "client_ip" => $request->getClientIp(),
                "notification_url" => $notificationUrl,
            ];

            $jsonRequest = json_encode($requestBody, JSON_THROW_ON_ERROR);

            $response = $this->pspService->postInvoice($jsonRequest);

            $jsonResponse = json_encode($response, JSON_THROW_ON_ERROR);

            $invoice->setExpirationDate(new DateTimeImmutable("+1 day"));
            $invoice->setRequest($jsonRequest);
            $invoice->setResponse($jsonResponse);
            $invoice->setOrder($order);
            $invoice->setStatus(InvoiceStatusEnum::CREATED);
            $invoice->setNotificationUrl($notificationUrl);

            $this->entityManager->persist($invoice);
            $this->entityManager->flush();

            return $this->redirectToRoute('show_invoice', ['id' => $invoice->getId()]);
        }

        return $this->render('/invoice/create.html.twig', [
            'form' => $form,
        ]);
    }

    #[Route('/invoice/{id}', name: 'show_invoice', methods: ['GET'])]
    public function show(Invoice $invoice, Request $request): Response
    {
        $serializer = new Serializer([new ObjectNormalizer()], [new JsonEncoder()]);
        $invoiceData = $serializer->deserialize($invoice->getResponse(), InvoiceResponseModel::class, 'json');
        return $this->render(
            'invoice/show.html.twig',
            [
                'invoice' => $invoiceData,
                'invoiceId' => $invoice->getId(),
                'notificationUrl' => $invoice->getNotificationUrl(),
            ]
        );
    }
}
