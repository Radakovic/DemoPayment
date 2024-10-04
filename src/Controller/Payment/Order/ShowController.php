<?php

namespace App\Controller\Payment\Order;

use App\Entity\Invoice;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Contracts\Cache\CacheInterface;
use Symfony\Contracts\Cache\ItemInterface;

class ShowController extends AbstractController
{
    public function __construct(private readonly CacheInterface $cache)
    {
    }

    #[Route('/invoices/{id}', name: 'show_invoice', methods: ['GET'])]
    public function __invoke(Invoice $invoice): Response
    {
        $id = $invoice->getId();
        $invoiceData = $this->cache->get("invoice_data_$id", function (ItemInterface $item) use ($invoice) {

            $item->expiresAfter(3600);
            return json_decode($invoice->getResponse(), true);
        });

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
