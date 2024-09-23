<?php

namespace App\Entity\Traits;

use Doctrine\DBAL\Types\Types;
use DateTimeImmutable;
use Doctrine\ORM\Mapping as ORM;

/**
 * Contains default entity timestamps
 * @codeCoverageIgnore
 */
trait DeletedAtTrait
{
    #[ORM\Column(type: Types::DATETIME_MUTABLE, nullable: true)]
    private ?DateTimeImmutable $deletedAt = null;

    public function getDeletedAt(): ?DateTimeImmutable
    {
        return $this->deletedAt;
    }

    public function setDeletedAt(?DateTimeImmutable $deletedAt): void
    {
        $this->deletedAt = $deletedAt;
    }
}
