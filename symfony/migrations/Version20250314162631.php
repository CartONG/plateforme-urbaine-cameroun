<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250314162631 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP INDEX idx_447556f9f98f144a');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_447556F9F98F144A ON actor (logo_id)');
        $this->addSql('ALTER TABLE media_object ALTER created_at DROP DEFAULT');
        $this->addSql('ALTER TABLE media_object ALTER created_at SET NOT NULL');
        $this->addSql('ALTER TABLE media_object ALTER updated_at DROP DEFAULT');
        $this->addSql('ALTER TABLE media_object ALTER updated_at SET NOT NULL');
        $this->addSql('ALTER TABLE media_object ALTER type DROP DEFAULT');
        $this->addSql('ALTER TABLE media_object ALTER type SET NOT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('DROP INDEX UNIQ_447556F9F98F144A');
        $this->addSql('CREATE INDEX idx_447556f9f98f144a ON actor (logo_id)');
        $this->addSql('ALTER TABLE media_object ALTER created_at SET DEFAULT \'2025-02-18 12:00:00\'');
        $this->addSql('ALTER TABLE media_object ALTER created_at DROP NOT NULL');
        $this->addSql('ALTER TABLE media_object ALTER updated_at SET DEFAULT \'2025-02-18 12:00:00\'');
        $this->addSql('ALTER TABLE media_object ALTER updated_at DROP NOT NULL');
        $this->addSql('ALTER TABLE media_object ALTER type SET DEFAULT \'file\'');
        $this->addSql('ALTER TABLE media_object ALTER type DROP NOT NULL');
    }
}
