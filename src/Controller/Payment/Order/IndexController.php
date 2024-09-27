<?php

namespace App\Controller\Payment\Order;

use App\Repository\MerchantOrderRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class IndexController extends AbstractController
{
    public function __construct(
        private readonly MerchantOrderRepository $orderRepository,
    ) {
    }

    /**
     * Controller for showing all orders.
     * In reality probably it should show orders only for one merchant.
     */
    #[Route(path: '/', name: 'merchant_order_index', methods: ['GET'])]
    public function __invoke(): Response
    {
        // Here should be logic for finding orders, not like in this example
        $merchantOrders = $this->orderRepository->findAll();

        return $this->render('/invoice/index.html.twig', [
            'orders' => $merchantOrders,
        ]);
    }
}
