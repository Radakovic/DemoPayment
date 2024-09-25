<?php

namespace App\Entity;

use App\Entity\Traits\CreateAndUpdatedAtTrait;
use App\Repository\CallbackRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Ramsey\Uuid\Uuid;
use Ramsey\Uuid\UuidInterface;

#[ORM\Table(schema: 'payment')]
#[ORM\Entity(repositoryClass: CallbackRepository::class)]
class Callback
{
    use CreateAndUpdatedAtTrait;

    public function __construct(
        #[ORM\Column(type: Types::JSON)]
        private ?string $response = null,
        #[ORM\Column(type: Types::JSON)]
        private ?string $request = null,
        #[ORM\ManyToOne(
            targetEntity: Invoice::class,
            cascade: ['persist', 'remove'],
            inversedBy: 'callbacks'
        )]
        private ?Invoice $invoice = null,
        #[ORM\Id]
        #[ORM\Column(type: 'uuid', unique: true)]
        private ?UuidInterface $id = null,
    ) {
        $this->id = $id  ?? Uuid::uuid4();
    }

    public function getId(): UuidInterface
    {
        return $this->id;
    }
    public function getResponse(): ?string
    {
        return $this->response;
    }

    public function setResponse(string $response): void
    {
        $this->response = $response;
    }
    public function getInvoice(): ?Invoice
    {
        return $this->invoice;
    }

    public function setInvoice(?Invoice $invoice): void
    {
        $this->invoice = $invoice;
    }

    public function getRequest(): ?string
    {
        return $this->request;
    }

    public function setRequest(?string $request): void
    {
        $this->request = $request;
    }
}
