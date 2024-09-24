<?php

namespace App\Enum;

use Symfony\Contracts\Translation\TranslatableInterface;
use Symfony\Contracts\Translation\TranslatorInterface;

enum InvoiceStatusEnum: string implements TranslatableInterface
{
    case CREATED = 'CREATED';
    case PENDING = 'PENDING';
    case SUCCESSFUL = 'SUCCESSFUL';
    case ERROR = 'ERROR';
    case EXPIRED = 'EXPIRED';
    case REJECTED = 'REJECTED';

    public function trans(TranslatorInterface $translator, ?string $locale = null): string
    {
        return $translator->trans($this->value, [], $locale);
    }
}
