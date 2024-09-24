<?php

namespace App\Model;

readonly class InvoiceResponseModel
{
    public function __construct(
        private string $redirect_url,
        private string $deposit_id,
        private string $user_id,
        private string $merchant_order_id,
        private array  $payment_info,
    ) {
    }

    public function getRedirectUrl(): string
    {
        return $this->redirect_url;
    }
    public function getDepositId(): string
    {
        return $this->deposit_id;
    }
    public function getUserId(): string
    {
        return $this->user_id;
    }
    public function getMerchantOrderId(): string
    {
        return $this->merchant_order_id;
    }
    public function getPaymentInfo(): array
    {
        return $this->payment_info;
    }
}
