<?php

namespace App\Controller\Payment\Order;

use App\Entity\Invoice;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class ShowController extends AbstractController
{
    #[Route('/orders/{id}', name: 'show_invoice', methods: ['GET'])]
    public function __invoke(Invoice $invoice): Response
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
}
