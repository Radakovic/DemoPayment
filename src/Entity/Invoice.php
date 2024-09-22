<?php

namespace App\Entity;

use App\Entity\Traits\CreateAndUpdatedAtTrait;
use App\Entity\Traits\DeletedAtTrait;
use App\Enum\InvoiceStatusEnum;
use App\Repository\InvoiceRepository;
use DateTimeImmutable;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Ramsey\Uuid\Uuid;
use Ramsey\Uuid\UuidInterface;

#[ORM\Table(schema: 'payment')]
#[ORM\Entity(repositoryClass: InvoiceRepository::class)]
class Invoice
{
    use CreateAndUpdatedAtTrait;
    use DeletedAtTrait;

    public function __construct(
        #[ORM\Column(type: Types::BIGINT)]
        private int $amount,
        #[ORM\Column(type: Types::STRING, length: 10)]
        private string $country,
        #[ORM\Column(type: Types::STRING, length: 10)]
        private string $currency,
        #[ORM\Column(type: Types::JSON)]
        private string $payer,
        #[ORM\Column(type: Types::STRING)]
        private string $payment_method,
        #[ORM\Column(type: Types::STRING)]
        private string $clientIp,
        #[ORM\Column(type: 'string')]
        private string $notificationUrl,
        #[ORM\Column(type: Types::JSON)]
        private string $request,
        #[ORM\Column(type: Types::JSON)]
        private string $response,
        #[ORM\Column(type: Types::DATETIME_IMMUTABLE)]
        private DateTimeImmutable $expirationDate,
        #[ORM\Column(type: 'string', enumType: InvoiceStatusEnum::class)]
        private InvoiceStatusEnum $status,
        #[ORM\Id]
        #[ORM\Column(type: 'uuid', unique: true)]
        private ?UuidInterface $id,
        #[ORM\Column(type: Types::TEXT, nullable: true)]
        private ?string $description,
    ) {
        $this->id = $id  ?? Uuid::uuid4();
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

    public function getPayer(): string
    {
        return $this->payer;
    }

    public function setPayer(string $payer): void
    {
        $this->payer = $payer;
    }

    public function getPaymentMethod(): string
    {
        return $this->payment_method;
    }

    public function setPaymentMethod(string $payment_method): void
    {
        $this->payment_method = $payment_method;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): void
    {
        $this->description = $description;
    }

    public function getClientIp(): string
    {
        return $this->clientIp;
    }

    public function setClientIp(string $clientIp): void
    {
        $this->clientIp = $clientIp;
    }

    public function getNotificationUrl(): string
    {
        return $this->notificationUrl;
    }

    public function setNotificationUrl(string $notificationUrl): void
    {
        $this->notificationUrl = $notificationUrl;
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

    public function getStatus(): InvoiceStatusEnum
    {
        return $this->status;
    }

    public function setStatus(InvoiceStatusEnum $status): void
    {
        $this->status = $status;
    }
}
