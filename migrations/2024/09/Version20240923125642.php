<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240923125642 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Create relations between order and invoice';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('ALTER TABLE payment.invoice ADD order_id UUID DEFAULT NULL');
        $this->addSql('COMMENT ON COLUMN payment.invoice.order_id IS \'(DC2Type:uuid)\'');
        $this->addSql('ALTER TABLE payment.invoice ADD CONSTRAINT FK_E9D688A08D9F6D38 FOREIGN KEY (order_id) REFERENCES payment.order (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_E9D688A08D9F6D38 ON payment.invoice (order_id)');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('ALTER TABLE payment.invoice DROP CONSTRAINT FK_E9D688A08D9F6D38');
        $this->addSql('DROP INDEX payment.UNIQ_E9D688A08D9F6D38');
        $this->addSql('ALTER TABLE payment.invoice DROP order_id');
    }
}
