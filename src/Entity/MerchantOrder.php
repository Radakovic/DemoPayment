<?php

namespace App\Entity;

use App\Entity\Traits\CreateAndUpdatedAtTrait;
use App\Entity\Traits\DeletedAtTrait;
use App\Repository\MerchantOrderRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Ramsey\Uuid\Uuid;
use Ramsey\Uuid\UuidInterface;

#[ORM\Table(schema: 'payment')]
#[Gedmo\SoftDeleteable(hardDelete: false)]
#[ORM\Entity(repositoryClass: MerchantOrderRepository::class)]
class MerchantOrder
{
    use CreateAndUpdatedAtTrait;
    use DeletedAtTrait;

    public function __construct(
        #[ORM\Column(type: Types::BIGINT)]
        private ?int $amount = null,
        #[ORM\Column(length: 10)]
        private ?string $country = null,
        #[ORM\Column(length: 10)]
        private ?string $currency = null,
        #[ORM\Id]
        #[ORM\Column(type: 'uuid', unique: true)]
        private ?UuidInterface $id = null,
        #[ORM\OneToOne(
            targetEntity: Invoice::class,
            inversedBy: 'order',
            cascade: ['persist', 'remove'],
            orphanRemoval: true,
        )]
        #[ORM\JoinColumn(name: 'invoice_id', referencedColumnName: 'id', nullable: true)]
        private ?Invoice $invoice = null,
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
