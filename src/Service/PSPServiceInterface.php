<?php

namespace App\Service;

interface PSPServiceInterface
{
    public function postInvoice(array $requestBody): array;
    public function signData(string $body): string;
}
