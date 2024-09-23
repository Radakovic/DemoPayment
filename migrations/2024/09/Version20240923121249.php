<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240923121249 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Create new enum type for invoice status';
    }

    public function up(Schema $schema): void
    {
        $this->addSql("CREATE TYPE payment.invoice_status AS ENUM (
            'CREATED',
            'PENDING',
            'SUCCESSFUL',
            'ERROR',
            'EXPIRED',
            'REJECTED'
        )");
    }

    public function down(Schema $schema): void
    {
        $this->addSql('DROP TYPE payment.invoice_status');
    }
}
