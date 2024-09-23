<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240923212305 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('CREATE TABLE payment.invoice (
            id UUID NOT NULL,
            order_id UUID DEFAULT NULL,
            payer JSON NOT NULL,
            payment_method VARCHAR(255) NOT NULL,
            client_ip VARCHAR(255) NOT NULL,
            notification_url VARCHAR(255) NOT NULL,
            request JSON NOT NULL,
            response JSON NOT NULL,
            expiration_date TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL,
            status invoice_status NOT NULL,
            description TEXT DEFAULT NULL,
            created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL,
            updated_at TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL,
            deleted_at TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL,
            PRIMARY KEY(id))'
        );
        $this->addSql('CREATE UNIQUE INDEX UNIQ_E9D688A08D9F6D38 ON payment.invoice (order_id)');
        $this->addSql('COMMENT ON COLUMN payment.invoice.id IS \'(DC2Type:uuid)\'');
        $this->addSql('COMMENT ON COLUMN payment.invoice.order_id IS \'(DC2Type:uuid)\'');
        $this->addSql('COMMENT ON COLUMN payment.invoice.expiration_date IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('COMMENT ON COLUMN payment.invoice.created_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('COMMENT ON COLUMN payment.invoice.updated_at IS \'(DC2Type:datetime_immutable)\'');

        $this->addSql('CREATE TABLE payment."order" (
            id UUID NOT NULL,
            amount BIGINT NOT NULL,
            country VARCHAR(10) NOT NULL,
            currency VARCHAR(10) NOT NULL,
            created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL,
            updated_at TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL,
            deleted_at TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL,
            PRIMARY KEY(id))'
        );
        $this->addSql('COMMENT ON COLUMN payment."order".id IS \'(DC2Type:uuid)\'');
        $this->addSql('COMMENT ON COLUMN payment."order".created_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('COMMENT ON COLUMN payment."order".updated_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('ALTER TABLE payment.invoice ADD CONSTRAINT FK_E9D688A08D9F6D38 FOREIGN KEY (order_id) REFERENCES payment."order" (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('ALTER TABLE payment.invoice DROP CONSTRAINT FK_E9D688A08D9F6D38');
        $this->addSql('DROP TABLE payment.invoice');
        $this->addSql('DROP TABLE payment."order"');
    }
}
