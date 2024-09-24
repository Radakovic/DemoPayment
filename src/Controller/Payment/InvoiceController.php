<?php

namespace App\Controller\Payment;

use App\Entity\Invoice;
use App\Entity\Order;
use App\Form\InvoiceType;
use App\Repository\OrderRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class InvoiceController extends AbstractController
{
    public function __construct(
        private readonly OrderRepository $orderRepository,
    ) {
    }

    #[Route('/', name: 'show', methods: ['GET'])]
    public function show(): Response
    {
        // Here should be logic for finding correct order
        // Currently im just taking one from fixture data
        $orders = $this->orderRepository->findAll();
        $order = $orders[array_rand($orders)];
        assert($order instanceof Order);

        $invoice = new Invoice();
        $invoice->setOrder($order);
        $form = $this->createForm(InvoiceType::class, $invoice);

        return $this->render('/invoice/show.html.twig', [
            'form' => $form,
        ]);
    }
}
