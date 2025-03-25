<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250218151131 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE project_media_object (project_id UUID NOT NULL, media_object_id INT NOT NULL, PRIMARY KEY(project_id, media_object_id))');
        $this->addSql('CREATE INDEX IDX_3E73D551166D1F9C ON project_media_object (project_id)');
        $this->addSql('CREATE INDEX IDX_3E73D55164DE5A5 ON project_media_object (media_object_id)');
        $this->addSql('COMMENT ON COLUMN project_media_object.project_id IS \'(DC2Type:uuid)\'');
        $this->addSql('CREATE TABLE projects_partners_media_object (project_id UUID NOT NULL, media_object_id INT NOT NULL, PRIMARY KEY(project_id, media_object_id))');
        $this->addSql('CREATE INDEX IDX_27A78B7C166D1F9C ON projects_partners_media_object (project_id)');
        $this->addSql('CREATE INDEX IDX_27A78B7C64DE5A5 ON projects_partners_media_object (media_object_id)');
        $this->addSql('COMMENT ON COLUMN projects_partners_media_object.project_id IS \'(DC2Type:uuid)\'');
        $this->addSql('ALTER TABLE project_media_object ADD CONSTRAINT FK_3E73D551166D1F9C FOREIGN KEY (project_id) REFERENCES project (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE project_media_object ADD CONSTRAINT FK_3E73D55164DE5A5 FOREIGN KEY (media_object_id) REFERENCES media_object (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE projects_partners_media_object ADD CONSTRAINT FK_27A78B7C166D1F9C FOREIGN KEY (project_id) REFERENCES project (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE projects_partners_media_object ADD CONSTRAINT FK_27A78B7C64DE5A5 FOREIGN KEY (media_object_id) REFERENCES media_object (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE media_object ADD original_name VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE media_object ADD mime_type VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE media_object ADD dimensions JSON DEFAULT NULL');
        $this->addSql('ALTER TABLE media_object ADD size INT DEFAULT NULL');
        $this->addSql('ALTER TABLE media_object ADD created_at TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT \'2025-02-18 12:00:00\'');
        $this->addSql('ALTER TABLE media_object ADD updated_at TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT \'2025-02-18 12:00:00\'');
        $this->addSql('ALTER TABLE media_object ADD type VARCHAR(255) DEFAULT \'file\'');
        $this->addSql('ALTER TABLE project ADD logo_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE project ADD external_images TEXT DEFAULT NULL');
        $this->addSql('ALTER TABLE project DROP images');
        $this->addSql('ALTER TABLE project DROP partners');
        $this->addSql('ALTER TABLE project DROP logo');
        $this->addSql('COMMENT ON COLUMN project.external_images IS \'(DC2Type:simple_array)\'');
        $this->addSql('ALTER TABLE project ADD CONSTRAINT FK_2FB3D0EEF98F144A FOREIGN KEY (logo_id) REFERENCES media_object (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE INDEX IDX_2FB3D0EEF98F144A ON project (logo_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE project_media_object DROP CONSTRAINT FK_3E73D551166D1F9C');
        $this->addSql('ALTER TABLE project_media_object DROP CONSTRAINT FK_3E73D55164DE5A5');
        $this->addSql('ALTER TABLE projects_partners_media_object DROP CONSTRAINT FK_27A78B7C166D1F9C');
        $this->addSql('ALTER TABLE projects_partners_media_object DROP CONSTRAINT FK_27A78B7C64DE5A5');
        $this->addSql('DROP TABLE project_media_object');
        $this->addSql('DROP TABLE projects_partners_media_object');
        $this->addSql('ALTER TABLE media_object DROP original_name');
        $this->addSql('ALTER TABLE media_object DROP mime_type');
        $this->addSql('ALTER TABLE media_object DROP dimensions');
        $this->addSql('ALTER TABLE media_object DROP size');
        $this->addSql('ALTER TABLE media_object DROP created_at');
        $this->addSql('ALTER TABLE media_object DROP updated_at');
        $this->addSql('ALTER TABLE media_object DROP type');
        $this->addSql('ALTER TABLE project DROP CONSTRAINT FK_2FB3D0EEF98F144A');
        $this->addSql('DROP INDEX IDX_2FB3D0EEF98F144A');
        $this->addSql('ALTER TABLE project ADD images JSON DEFAULT NULL');
        $this->addSql('ALTER TABLE project ADD partners JSON DEFAULT NULL');
        $this->addSql('ALTER TABLE project ADD logo VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE project DROP logo_id');
        $this->addSql('ALTER TABLE project DROP external_images');
    }
}
