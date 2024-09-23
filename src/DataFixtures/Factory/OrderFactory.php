<?php

namespace App\DataFixtures\Factory;

use App\Entity\Order;
use Ramsey\Uuid\Uuid;
use Ramsey\Uuid\UuidInterface;

class OrderFactory extends AbstractFactory
{
    public function __invoke(
        ?UuidInterface $id = null,
        ?int $amount = null,
        ?string $country = null,
        ?string $currency = null,
    ): Order {
        $id = $id ?? Uuid::uuid4();
        $amount = $amount ?? $this->faker->randomNumber(4, true);
        $country = $country ?? $this->faker->randomElement(['US', 'RS']);
        $currency = $currency ?? $country === 'US' ? 'USD' : 'RSD';

        return new Order(
            amount: $amount,
            country: $country,
            currency: $currency,
            id: $id
        );
    }
}
