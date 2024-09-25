<?php

namespace App\Service;

interface PSPServiceInterface
{
    public function postInvoice(string $requestBody): array;
    public function postCallback(array $request): array;
    public function signData(string $body): string;
}
