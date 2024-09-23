<?php

namespace App\Doctrine\EnumType;

use App\Enum\InvoiceStatusEnum;

class InvoiceStatusEnumType extends AbstractEnumType
{
    public function getEnumClass(): string
    {
        return InvoiceStatusEnum::class;
    }

    public function getPostgresName(): string
    {
        return 'invoice_status';
    }

    public function getName(): string
    {
        return 'invoice_status';
    }
}
