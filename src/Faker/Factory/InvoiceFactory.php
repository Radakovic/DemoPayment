<?php

namespace App\Faker\Factory;

use App\DataFixtures\Factory\AbstractFactory;
use App\Entity\Callback;
use App\Entity\Invoice;
use App\Entity\MerchantOrder;
use App\Enum\InvoiceStatusEnum;
use DateTimeImmutable;
use Doctrine\ORM\EntityManagerInterface;
use Ramsey\Uuid\Uuid;
use Ramsey\Uuid\UuidInterface;
use const App\DataFixtures\Factory\MerchantOrderFactory;

class InvoiceFactory extends AbstractFactory
{
    public function __construct(iterable $factories, EntityManagerInterface $entityManager)
    {
        parent::__construct($factories, $entityManager);
    }

    public function getEntity(): string
    {
        return Invoice::class;
    }

    public function __invoke(
        ?UuidInterface $id = null,
        ?string $request = null,
        ?string $response = null,
        ?DateTimeImmutable $expirationDate = null,
        ?InvoiceStatusEnum $status = null,
        ?string $notificationUrl = null,
        ?MerchantOrder $order = null,
        ?string $paymentMethod = null,
        ?string $description = null,
        ?Callback $callback = null,
    ): Invoice {
        $id = $id ?? Uuid::uuid4();
        $paymentMethod = $paymentMethod ?? 'XXX';
        $expirationDate = $expirationDate ?? new DateTimeImmutable('+1 day');
        $status = $status ?? $this->faker->randomElement(InvoiceStatusEnum::cases());
        $notificationUrl = $notificationUrl ?? $this->faker->url();
        $description = $description ?? $this->faker->sentence();
        $order = $order ?? $this->createEntityIfNotExists(MerchantOrderFactory);

        $invoice = new Invoice(
            paymentMethod: $paymentMethod,
            request: '',
            response: '',
            expirationDate: $expirationDate,
            status: $status,
            notificationUrl: $notificationUrl,
            order: $order,
            id: $id,
            description: $description,
            callbacks: null
        );

        return $invoice;
    }
}
