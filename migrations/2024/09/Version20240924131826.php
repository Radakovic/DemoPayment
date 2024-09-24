<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240924131826 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE payment.invoice (id UUID NOT NULL, payment_method VARCHAR(255) NOT NULL, request JSON NOT NULL, response JSON NOT NULL, expiration_date TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, status VARCHAR(255) NOT NULL, description TEXT DEFAULT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, updated_at TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL, deleted_at TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('COMMENT ON COLUMN payment.invoice.id IS \'(DC2Type:uuid)\'');
        $this->addSql('COMMENT ON COLUMN payment.invoice.expiration_date IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('COMMENT ON COLUMN payment.invoice.created_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('COMMENT ON COLUMN payment.invoice.updated_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('CREATE TABLE payment.merchant_order (id UUID NOT NULL, invoice_id UUID DEFAULT NULL, amount BIGINT NOT NULL, country VARCHAR(10) NOT NULL, currency VARCHAR(10) NOT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, updated_at TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL, deleted_at TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_3835F29F2989F1FD ON payment.merchant_order (invoice_id)');
        $this->addSql('COMMENT ON COLUMN payment.merchant_order.id IS \'(DC2Type:uuid)\'');
        $this->addSql('COMMENT ON COLUMN payment.merchant_order.invoice_id IS \'(DC2Type:uuid)\'');
        $this->addSql('COMMENT ON COLUMN payment.merchant_order.created_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('COMMENT ON COLUMN payment.merchant_order.updated_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('ALTER TABLE payment.merchant_order ADD CONSTRAINT FK_3835F29F2989F1FD FOREIGN KEY (invoice_id) REFERENCES payment.invoice (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE payment.merchant_order DROP CONSTRAINT FK_3835F29F2989F1FD');
        $this->addSql('DROP TABLE payment.invoice');
        $this->addSql('DROP TABLE payment.merchant_order');
    }
}
