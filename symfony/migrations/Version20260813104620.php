<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Cleaned manually
 */
final class Version20260813104620 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Modifications sur divercity.booking et indexation de booking_attachment';
    }

    public function up(Schema $schema): void
    {
        // 1. Modifications sur la table booking
        $this->addSql(<<<'SQL'
            ALTER TABLE divercity.booking ALTER title SET NOT NULL
        SQL);

        // 2. Suppression des anciens index
        $this->addSql(<<<'SQL'
            DROP INDEX IF EXISTS idx_booking_attachment_type
        SQL);
        $this->addSql(<<<'SQL'
            DROP INDEX IF EXISTS idx_booking_attachment_media_object
        SQL);

        // 3. Ajustement des colonnes et types sur booking_attachment
        $this->addSql(<<<'SQL'
            ALTER TABLE divercity.booking_attachment ALTER id DROP DEFAULT
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE divercity.booking_attachment ALTER booking_id TYPE UUID
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE divercity.booking_attachment ALTER created_at DROP DEFAULT
        SQL);
        $this->addSql(<<<'SQL'
            COMMENT ON COLUMN divercity.booking_attachment.booking_id IS '(DC2Type:uuid)'
        SQL);

        // 4. Recréation de l'index sur la nouvelle colonne file_object_id et renommage des index
        $this->addSql(<<<'SQL'
            CREATE INDEX IF NOT EXISTS IDX_3FD2BC99AD22E95D ON divercity.booking_attachment (file_object_id)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER INDEX IF EXISTS divercity.idx_booking_attachment_booking RENAME TO IDX_3FD2BC993301C60
        SQL);
    }

    public function down(Schema $schema): void
    {
        $this->addSql(<<<'SQL'
            ALTER TABLE divercity.booking ALTER title DROP NOT NULL
        SQL);
        $this->addSql(<<<'SQL'
            DROP INDEX IF EXISTS IDX_3FD2BC99AD22E95D
        SQL);
        $this->addSql(<<<'SQL'
            CREATE SEQUENCE IF NOT EXISTS divercity.booking_attachment_id_seq
        SQL);
        $this->addSql(<<<'SQL'
            SELECT setval('divercity.booking_attachment_id_seq', (SELECT MAX(id) FROM divercity.booking_attachment))
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE divercity.booking_attachment ALTER id SET DEFAULT nextval('divercity.booking_attachment_id_seq')
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE divercity.booking_attachment ALTER booking_id TYPE UUID
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE divercity.booking_attachment ALTER created_at SET DEFAULT 'now()'
        SQL);
        $this->addSql(<<<'SQL'
            COMMENT ON COLUMN divercity.booking_attachment.booking_id IS NULL
        SQL);
        $this->addSql(<<<'SQL'
            CREATE INDEX IF NOT EXISTS idx_booking_attachment_type ON divercity.booking_attachment (booking_id, type)
        SQL);
        $this->addSql(<<<'SQL'
            CREATE INDEX IF NOT EXISTS idx_booking_attachment_media_object ON divercity.booking_attachment (file_object_id)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER INDEX IF EXISTS divercity.idx_3fd2bc993301c60 RENAME TO idx_booking_attachment_booking
        SQL);
    }
}