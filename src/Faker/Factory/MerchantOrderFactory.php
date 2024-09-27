<?php

namespace App\Faker\Factory;

use App\DataFixtures\Factory\AbstractFactory;
use App\Entity\MerchantOrder;
use Ramsey\Uuid\Uuid;
use Ramsey\Uuid\UuidInterface;

class MerchantOrderFactory extends AbstractFactory
{
    public function __invoke(
        ?UuidInterface $id = null,
        ?int $amount = null,
        ?string $country = null,
        ?string $currency = null,
    ): MerchantOrder {
        $id = $id ?? Uuid::uuid4();
        $amount = $amount ?? $this->faker->randomNumber(5, true);
        $country = $country ?? $this->faker->randomElement(['US', 'RS']);
        $currency = $currency ?? $country === 'US' ? 'USD' : 'RSD';

        return new MerchantOrder(
            amount: $amount,
            country: $country,
            currency: $currency,
            id: $id
        );
    }

    public function getEntity(): string
    {
        return MerchantOrder::class;
    }
}
