<?php

namespace App\Controller\PSPServiceSimulator;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Attribute\Route;

class PSPServiceSimulator extends AbstractController
{
    #[Route(path: '/invoice_simulator', name: 'invoice_simulator', methods: ['POST'])]
    public function invoicePostRequest(): array
    {
        return [];
    }
}
