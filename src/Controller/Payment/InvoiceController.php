<?php

namespace App\Controller\Payment;

use App\Entity\Invoice;
use App\Entity\Order;
use App\Form\InvoiceType;
use App\Repository\OrderRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class InvoiceController extends AbstractController
{
    public function __construct(
        private readonly OrderRepository $orderRepository,
    ) {
    }

    #[Route('/', name: 'show', methods: ['GET', 'POST'])]
    public function show(Request $request): Response
    {
        // Here should be logic for finding correct order by some parameter
        // Something like findOneBy(), or find()
        // Currently im just taking one from fixture data
        $orders = $this->orderRepository->findAll();
        $order = $orders[array_rand($orders)];
        assert($order instanceof Order);

        $invoice = new Invoice();
        $invoice->setOrder($order);
        $form = $this->createForm(InvoiceType::class, $invoice);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $invoiceForm = $form->getData();
            $payer = json_encode($form->get('payer')->getData(), JSON_THROW_ON_ERROR);
            $invoice->setPayer($payer);

            // ... perform some action, such as saving the task to the database

            //return $this->redirectToRoute('task_success');
        }

        return $this->render('/invoice/show.html.twig', [
            'form' => $form,
        ]);
    }
}
