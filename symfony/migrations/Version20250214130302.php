<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250214130302 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE projects_partners_media_object (project_id UUID NOT NULL, media_object_id INT NOT NULL, PRIMARY KEY(project_id, media_object_id))');
        $this->addSql('CREATE INDEX IDX_27A78B7C166D1F9C ON projects_partners_media_object (project_id)');
        $this->addSql('CREATE INDEX IDX_27A78B7C64DE5A5 ON projects_partners_media_object (media_object_id)');
        $this->addSql('COMMENT ON COLUMN projects_partners_media_object.project_id IS \'(DC2Type:uuid)\'');
        $this->addSql('ALTER TABLE projects_partners_media_object ADD CONSTRAINT FK_27A78B7C166D1F9C FOREIGN KEY (project_id) REFERENCES project (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE projects_partners_media_object ADD CONSTRAINT FK_27A78B7C64DE5A5 FOREIGN KEY (media_object_id) REFERENCES file (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE project DROP partners');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE projects_partners_media_object DROP CONSTRAINT FK_27A78B7C166D1F9C');
        $this->addSql('ALTER TABLE projects_partners_media_object DROP CONSTRAINT FK_27A78B7C64DE5A5');
        $this->addSql('DROP TABLE projects_partners_media_object');
        $this->addSql('ALTER TABLE project ADD partners JSON DEFAULT NULL');
    }
}
