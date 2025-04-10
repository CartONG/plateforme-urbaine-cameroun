<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250326123913 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE media_object ADD created_by INT DEFAULT NULL');
        $this->addSql('ALTER TABLE media_object ADD updated_by INT DEFAULT NULL');
        $this->addSql('ALTER TABLE media_object ADD CONSTRAINT FK_14D43132DE12AB56 FOREIGN KEY (created_by) REFERENCES "user" (id) ON DELETE SET NULL NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE media_object ADD CONSTRAINT FK_14D4313216FE72E1 FOREIGN KEY (updated_by) REFERENCES "user" (id) ON DELETE SET NULL NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE INDEX IDX_14D43132DE12AB56 ON media_object (created_by)');
        $this->addSql('CREATE INDEX IDX_14D4313216FE72E1 ON media_object (updated_by)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE media_object DROP CONSTRAINT FK_14D43132DE12AB56');
        $this->addSql('ALTER TABLE media_object DROP CONSTRAINT FK_14D4313216FE72E1');
        $this->addSql('DROP INDEX IDX_14D43132DE12AB56');
        $this->addSql('DROP INDEX IDX_14D4313216FE72E1');
        $this->addSql('ALTER TABLE media_object DROP created_by');
        $this->addSql('ALTER TABLE media_object DROP updated_by');
    }
}
