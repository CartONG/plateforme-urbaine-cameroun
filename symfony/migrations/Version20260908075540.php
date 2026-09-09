<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20260908075540 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            CREATE SEQUENCE project_resource_id_seq INCREMENT BY 1 MINVALUE 1 START 1
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE project_resource (id INT NOT NULL, project_id UUID NOT NULL, file_object_id INT NOT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, PRIMARY KEY(id))
        SQL);
        $this->addSql(<<<'SQL'
            CREATE INDEX IDX_81DF7FCD166D1F9C ON project_resource (project_id)
        SQL);
        $this->addSql(<<<'SQL'
            CREATE INDEX IDX_81DF7FCDAD22E95D ON project_resource (file_object_id)
        SQL);
        $this->addSql(<<<'SQL'
            COMMENT ON COLUMN project_resource.project_id IS '(DC2Type:uuid)'
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE project_resource ADD CONSTRAINT FK_81DF7FCD166D1F9C FOREIGN KEY (project_id) REFERENCES project (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE project_resource ADD CONSTRAINT FK_81DF7FCDAD22E95D FOREIGN KEY (file_object_id) REFERENCES media_object (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE
        SQL);
        $this->addSql(<<<'SQL'
            DROP INDEX IF EXISTS idx_booking_attachment_type
        SQL);
        $this->addSql(<<<'SQL'
            DROP INDEX IF EXISTS idx_booking_attachment_media_object
        SQL);
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            CREATE SCHEMA public
        SQL);
        $this->addSql(<<<'SQL'
            DROP SEQUENCE project_resource_id_seq CASCADE
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE project_resource DROP CONSTRAINT FK_81DF7FCD166D1F9C
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE project_resource DROP CONSTRAINT FK_81DF7FCDAD22E95D
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE project_resource
        SQL);
        $this->addSql(<<<'SQL'
            CREATE INDEX idx_booking_attachment_type ON divercity.booking_attachment (booking_id, type)
        SQL);
        $this->addSql(<<<'SQL'
            CREATE INDEX idx_booking_attachment_media_object ON divercity.booking_attachment (file_object_id)
        SQL);
    }
}
