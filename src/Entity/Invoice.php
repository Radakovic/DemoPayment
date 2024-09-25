<?php

namespace App\Entity;

use App\Entity\Traits\CreateAndUpdatedAtTrait;
use App\Entity\Traits\DeletedAtTrait;
use App\Enum\InvoiceStatusEnum;
use App\Repository\InvoiceRepository;
use DateTimeImmutable;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Ramsey\Uuid\Uuid;
use Ramsey\Uuid\UuidInterface;

#[ORM\Table(schema: 'payment')]
#[Gedmo\SoftDeleteable(hardDelete: false)]
#[ORM\Entity(repositoryClass: InvoiceRepository::class)]
class Invoice
{
    use CreateAndUpdatedAtTrait;
    use DeletedAtTrait;

    public function __construct(
        #[ORM\Column(type: Types::STRING)]
        private ?string $paymentMethod = null,
        #[ORM\Column(type: Types::JSON)]
        private ?string $request = null,
        #[ORM\Column(type: Types::JSON)]
        private ?string $response = null,
        #[ORM\Column(type: Types::DATETIME_IMMUTABLE)]
        private ?DateTimeImmutable $expirationDate = null,
        #[ORM\Column(type: 'string', nullable: false, enumType: InvoiceStatusEnum::class)]
        private ?InvoiceStatusEnum $status = null,
        #[ORM\Column(type: Types::STRING)]
        private ?string $notificationUrl = null,
        #[ORM\OneToOne(
            targetEntity: MerchantOrder::class,
            mappedBy: 'invoice',
            orphanRemoval: true,
        )]
        private ?MerchantOrder $order = null,
        #[ORM\Id]
        #[ORM\Column(type: 'uuid', unique: true)]
        private ?UuidInterface $id = null,
        #[ORM\Column(type: Types::TEXT, nullable: true)]
        private ?string $description = null,
        #[ORM\OneToMany(
            targetEntity: Callback::class,
            mappedBy: 'invoice',
            cascade: ['persist'],
            orphanRemoval: true
        )]
        private ?Collection $callbacks = null,
    ) {
        $this->id = $id  ?? Uuid::uuid4();
        $this->callbacks = $callbacks ?? new ArrayCollection();
    }

    public function getId(): UuidInterface
    {
        return $this->id;
    }

    public function getPaymentMethod(): string
    {
        return $this->paymentMethod;
    }

    public function setPaymentMethod(string $paymentMethod): void
    {
        $this->paymentMethod = $paymentMethod;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): void
    {
        $this->description = $description;
    }

    public function getRequest(): string
    {
        return $this->request;
    }

    public function setRequest(string $request): void
    {
        $this->request = $request;
    }

    public function getResponse(): string
    {
        return $this->response;
    }

    public function setResponse(string $response): void
    {
        $this->response = $response;
    }

    public function getExpirationDate(): DateTimeImmutable
    {
        return $this->expirationDate;
    }

    public function setExpirationDate(DateTimeImmutable $expirationDate): void
    {
        $this->expirationDate = $expirationDate;
    }

    public function getStatus(): ?InvoiceStatusEnum
    {
        return $this->status;
    }

    public function setStatus(InvoiceStatusEnum $status): void
    {
        $this->status = $status;
    }
    public function getOrder(): ?MerchantOrder
    {
        return $this->order;
    }
    public function setOrder(MerchantOrder $order): void
    {
        $this->order = $order;
    }

    public function getNotificationUrl(): ?string
    {
        return $this->notificationUrl;
    }

    public function setNotificationUrl(?string $notificationUrl): void
    {
        $this->notificationUrl = $notificationUrl;
    }

    public function getCallbacks(): ?Collection
    {
        return $this->callbacks;
    }
    public function addCallback(Callback $callback): void
    {
        if (!$this->callbacks->contains($callback)) {
            $this->callbacks->add($callback);
            $callback->setInvoice($this);
        }
    }
    public function removeCallback(Callback $callback): void
    {
        if ($this->callbacks->contains($callback)) {
            $this->callbacks->removeElement($callback);
        }
    }
}
