<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240923093958 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Make order table';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('CREATE TABLE payment.order (
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
        $this->addSql('ALTER TABLE payment.invoice DROP amount');
        $this->addSql('ALTER TABLE payment.invoice DROP country');
        $this->addSql('ALTER TABLE payment.invoice DROP currency');
        $this->addSql('ALTER TABLE payment.invoice DROP payer');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('DROP TABLE payment.order');
        $this->addSql('ALTER TABLE payment.invoice ADD amount BIGINT DEFAULT NULL');
        $this->addSql('ALTER TABLE payment.invoice ADD country VARCHAR(10) DEFAULT NULL');
        $this->addSql('ALTER TABLE payment.invoice ADD currency VARCHAR(10) DEFAULT NULL');
        $this->addSql('ALTER TABLE payment.invoice ADD payer JSON DEFAULT NULL');
    }
}
