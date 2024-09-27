<?php

namespace App\Tests\Unit\Enum;

use App\Enum\InvoiceStatusEnum;
use PHPUnit\Framework\TestCase;

class InvoiceStatusEnumTest extends TestCase
{
    public function testGetValues(): void
    {
        $cases = InvoiceStatusEnum::cases();

        foreach ($cases as $case) {
            self::assertNotNull(InvoiceStatusEnum::tryFrom($case->value));
        }
    }

    public function testGetEnumOfWrongValue(): void
    {
        $wrongCase = 'DUMMY';

        $wrongValue = InvoiceStatusEnum::tryFrom($wrongCase);

        $this->assertNull($wrongValue);
    }
}
