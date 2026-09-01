<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20260901073149 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            CREATE SEQUENCE divercity.highlighted_resource_id_seq INCREMENT BY 1 MINVALUE 1 START 1
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE divercity.highlighted_resource (id INT NOT NULL, resource_id VARCHAR(255) NOT NULL, is_highlighted BOOLEAN NOT NULL, highlighted_at TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL, position INT DEFAULT NULL, PRIMARY KEY(id))
        SQL);
        $this->addSql(<<<'SQL'
            CREATE UNIQUE INDEX UNIQ_5E74B5E889329D25 ON divercity.highlighted_resource (resource_id)
        SQL);
        $this->addSql(<<<'SQL'
            COMMENT ON COLUMN divercity.highlighted_resource.highlighted_at IS '(DC2Type:datetime_immutable)'
        SQL);
        $this->addSql(<<<'SQL'
            DROP INDEX IF EXISTS idx_booking_attachment_type
        SQL);
        $this->addSql(<<<'SQL'
            DROP INDEX IF EXISTS idx_booking_attachment_media_object
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE resource ALTER administrative_scopes DROP NOT NULL
        SQL);
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            CREATE SCHEMA public
        SQL);
        $this->addSql(<<<'SQL'
            DROP SEQUENCE divercity.highlighted_resource_id_seq CASCADE
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE divercity.highlighted_resource
        SQL);
        $this->addSql(<<<'SQL'
            CREATE INDEX idx_booking_attachment_type ON divercity.booking_attachment (booking_id, type)
        SQL);
        $this->addSql(<<<'SQL'
            CREATE INDEX idx_booking_attachment_media_object ON divercity.booking_attachment (file_object_id)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE resource ALTER administrative_scopes SET NOT NULL
        SQL);
    }
}
