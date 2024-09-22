<?php

namespace App\Enum;

enum InvoiceStatusEnum: string
{
    case CREATED = 'CREATED';
    case PENDING = 'PENDING';
    case SUCCESSFUL = 'SUCCESSFUL';
    case ERROR = 'ERROR';
    case EXPIRED = 'EXPIRED';
    case REJECTED = 'REJECTED';
}
