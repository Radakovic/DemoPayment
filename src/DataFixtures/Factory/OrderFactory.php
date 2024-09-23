<?php

namespace App\DataFixtures\Factory;

use App\Entity\Order;
use Doctrine\ORM\EntityManagerInterface;
use Faker\Generator;
use Ramsey\Uuid\Uuid;
use Ramsey\Uuid\UuidInterface;

readonly class OrderFactory
{
    public function __construct(
        public Generator $faker,
        public EntityManagerInterface $entityManager,
    ) {
    }

    public function __invoke(
        ?UuidInterface $id = null,
        ?int $amount = null,
        ?string $country = null,
        ?string $currency = null,
    ): void {
        $id = $id ?? Uuid::uuid4();
        $amount = $amount ?? $this->faker->randomNumber(4, true);
        $country = $country ?? 'US';
        $currency = $currency ?? 'USD';

        $order = new Order(
            amount: $amount,
            country: $country,
            currency: $currency,
            id: $id
        );

        $this->entityManager->persist($order);
    }
}
