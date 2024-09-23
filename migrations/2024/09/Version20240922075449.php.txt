<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240922075449 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Create invoice database table';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('CREATE TABLE payment.invoice (
            id UUID NOT NULL,
            amount BIGINT NOT NULL,
            country VARCHAR(10) NOT NULL,
            currency VARCHAR(10) NOT NULL,
            payer JSON NOT NULL,
            payment_method VARCHAR(255) NOT NULL,
            client_ip VARCHAR(255) NOT NULL,
            notification_url VARCHAR(255) NOT NULL,
            request JSON NOT NULL,
            response JSON NOT NULL,
            expiration_date TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL,
            status VARCHAR(255) NOT NULL,
            description TEXT DEFAULT NULL,
            created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL,
            updated_at TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL,
            deleted_at TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL,
            PRIMARY KEY(id))'
        );
        $this->addSql('COMMENT ON COLUMN payment.invoice.id IS \'(DC2Type:uuid)\'');
        $this->addSql('COMMENT ON COLUMN payment.invoice.expiration_date IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('COMMENT ON COLUMN payment.invoice.created_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('COMMENT ON COLUMN payment.invoice.updated_at IS \'(DC2Type:datetime_immutable)\'');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('DROP TABLE payment.invoice');
    }
}
