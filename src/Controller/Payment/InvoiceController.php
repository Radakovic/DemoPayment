<?php

namespace App\Controller\Payment;

use App\Entity\Invoice;
use App\Entity\Order;
use App\Form\InvoiceType;
use App\Repository\OrderRepository;
use App\Service\PSPServiceInterface;
use DateTimeImmutable;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class InvoiceController extends AbstractController
{
    public function __construct(
        private readonly OrderRepository $orderRepository,
        private readonly PSPServiceInterface $pspService
    ) {
    }

    #[Route('/', name: 'create', methods: ['GET', 'POST'])]
    public function create(Request $request): Response
    {
        $orders = $this->orderRepository->findAll();
        $order = $orders[0];
        assert($order instanceof Order);

        $invoice = new Invoice();
        $invoice->setOrder($order);
        $form = $this->createForm(InvoiceType::class, $invoice);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $invoiceForm = $form->getData();
            $order = $form->get('order')->getData();
            $payer = $form->get('payer')->getData();

            $requestBody = [
                "merchant_order_id" => $invoiceForm->getOrder()->getId()->toString(),
                "amount" => $invoiceForm->getOrder()->getAmount() / 100,
                "country" => $invoiceForm->getOrder()->getCountry(),
                "currency" => $invoiceForm->getOrder()->getCurrency(),
                "payer" => [
                    "document" => $payer['document'],
                    "first_name" => $payer['firstName'],
                    "last_name" => $payer['lastName'],
                    "phone" => $payer['phone'],
                    "email" => $payer['email'],
                ],
                "payment_method" => $invoiceForm->getPaymentMethod(),
                "description" => $invoiceForm->getDescription(),
                "client_ip" => $request->getClientIp(),
                "notification_url" => "https://www.your_domain.com/your/notification/url"
            ];
//            $invoiceForm = $form->getData();
//            $payer = json_encode($form->get('payer')->getData(), JSON_THROW_ON_ERROR);
//            $invoice->setPayer($payer);
//            $invoice->setClientIp($request->getClientIp());
//            $invoice->setExpirationDate(new DateTimeImmutable('+1 day'));

            $response = $this->pspService->postInvoice($requestBody);

            // ... perform some action, such as saving the task to the database

            //return $this->redirectToRoute('task_success');
        }

        return $this->render('/invoice/show.html.twig', [
            'form' => $form,
        ]);
    }
}
