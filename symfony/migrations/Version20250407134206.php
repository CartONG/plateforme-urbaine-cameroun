<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250407134206 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP INDEX idx_project_slug');
        $this->addSql('CREATE INDEX idx_project_slug_is_validated ON project (slug, is_validated)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('DROP INDEX idx_project_slug_is_validated');
        $this->addSql('ALTER TABLE project ALTER administrative_scopes DROP NOT NULL');
        $this->addSql('CREATE INDEX idx_project_slug ON project (slug)');
        $this->addSql('ALTER TABLE actor ALTER administrative_scopes DROP NOT NULL');
    }
}
