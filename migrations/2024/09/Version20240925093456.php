<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240925093456 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Create callback database table';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('CREATE TABLE payment.callback (
            id UUID NOT NULL,
            invoice_id UUID DEFAULT NULL,
            request JSON NOT NULL,
            response JSON NOT NULL,
            created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL,
            updated_at TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL,
            PRIMARY KEY(id))'
        );
        $this->addSql('CREATE INDEX IDX_DEE7E1D82989F1FD ON payment.callback (invoice_id)');
        $this->addSql('COMMENT ON COLUMN payment.callback.id IS \'(DC2Type:uuid)\'');
        $this->addSql('COMMENT ON COLUMN payment.callback.invoice_id IS \'(DC2Type:uuid)\'');
        $this->addSql('COMMENT ON COLUMN payment.callback.created_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('COMMENT ON COLUMN payment.callback.updated_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('ALTER TABLE payment.callback ADD CONSTRAINT FK_DEE7E1D82989F1FD FOREIGN KEY (invoice_id) REFERENCES payment.invoice (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('ALTER TABLE payment.callback DROP CONSTRAINT FK_DEE7E1D82989F1FD');
        $this->addSql('DROP TABLE payment.callback');
    }
}
