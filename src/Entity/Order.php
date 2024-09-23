<?php

namespace App\Entity;

use App\Entity\Traits\CreateAndUpdatedAtTrait;
use App\Entity\Traits\DeletedAtTrait;
use App\Repository\OrderRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Ramsey\Uuid\Uuid;
use Ramsey\Uuid\UuidInterface;

#[ORM\Table(schema: 'payment')]
#[ORM\Entity(repositoryClass: OrderRepository::class)]
class Order
{
    use CreateAndUpdatedAtTrait;
    use DeletedAtTrait;

    public function __construct(
        #[ORM\Column(type: Types::BIGINT)]
        private int $amount,
        #[ORM\Column(length: 10)]
        private string $country,
        #[ORM\Column(length: 10)]
        private string $currency,
        #[ORM\OneToOne(
            targetEntity: Invoice::class,
            mappedBy: 'order',
            cascade: ['persist', 'remove'],
            orphanRemoval: true,
        )]
        private ?Invoice $invoice,
        #[ORM\Id]
        #[ORM\Column(type: 'uuid', unique: true)]
        private ?UuidInterface $id,
    ) {
        $this->id = $id ?? Uuid::uuid4();
    }

    public function getId(): UuidInterface
    {
        return $this->id;
    }

    public function getAmount(): int
    {
        return $this->amount;
    }

    public function setAmount(int $amount): void
    {
        $this->amount = $amount;
    }

    public function getCountry(): string
    {
        return $this->country;
    }

    public function setCountry(string $country): void
    {
        $this->country = $country;
    }

    public function getCurrency(): string
    {
        return $this->currency;
    }

    public function setCurrency(string $currency): void
    {
        $this->currency = $currency;
    }
    public function getInvoice(): ?Invoice
    {
        return $this->invoice;
    }

    public function setInvoice(?Invoice $invoice): void
    {
        $this->invoice = $invoice;
    }
}
