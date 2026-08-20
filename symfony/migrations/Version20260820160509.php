<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20260820160509 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            DROP INDEX IF EXISTS idx_booking_attachment_type
        SQL);
        $this->addSql(<<<'SQL'
            DROP INDEX IF EXISTS idx_booking_attachment_media_object
        SQL);
        $this->addSql(<<<'SQL'
            COMMENT ON COLUMN divercity.space.description IS NULL
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE divercity.space_highlight DROP CONSTRAINT space_highlight_report_media_object_id_fkey
        SQL);
        $this->addSql(<<<'SQL'
            DROP INDEX IF EXISTS IDX_4E62BB7328A96488
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE divercity.space_highlight ALTER id DROP DEFAULT
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE divercity.space_highlight ALTER space_id TYPE UUID
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE divercity.space_highlight ALTER created_at DROP DEFAULT
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE divercity.space_highlight ALTER updated_at DROP DEFAULT
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE divercity.space_highlight RENAME COLUMN report_media_object_id TO report_file_object_id
        SQL);
        $this->addSql(<<<'SQL'
            COMMENT ON COLUMN divercity.space_highlight.space_id IS '(DC2Type:uuid)'
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE divercity.space_highlight ADD CONSTRAINT FK_4E62BB73FDAE4520 FOREIGN KEY (report_file_object_id) REFERENCES media_object (id) NOT DEFERRABLE INITIALLY IMMEDIATE
        SQL);
        $this->addSql(<<<'SQL'
            CREATE INDEX IDX_4E62BB73FDAE4520 ON divercity.space_highlight (report_file_object_id)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER INDEX divercity.idx_space_highlight_space RENAME TO IDX_4E62BB7323575340
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE divercity.space_statistic ALTER id DROP DEFAULT
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE divercity.space_statistic ALTER created_at DROP DEFAULT
        SQL);
        $this->addSql(<<<'SQL'
            ALTER INDEX divercity.idx_space_statistic_highlight RENAME TO IDX_E36125DB4B6C6223
        SQL);
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            CREATE SCHEMA public
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE divercity.space_highlight DROP CONSTRAINT FK_4E62BB73FDAE4520
        SQL);
        $this->addSql(<<<'SQL'
            DROP INDEX IDX_4E62BB73FDAE4520
        SQL);
        $this->addSql(<<<'SQL'
            CREATE SEQUENCE divercity.space_highlight_id_seq
        SQL);
        $this->addSql(<<<'SQL'
            SELECT setval('divercity.space_highlight_id_seq', (SELECT MAX(id) FROM divercity.space_highlight))
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE divercity.space_highlight ALTER id SET DEFAULT nextval('divercity.space_highlight_id_seq')
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE divercity.space_highlight ALTER space_id TYPE UUID
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE divercity.space_highlight ALTER created_at SET DEFAULT 'now()'
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE divercity.space_highlight ALTER updated_at SET DEFAULT 'now()'
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE divercity.space_highlight RENAME COLUMN report_file_object_id TO report_media_object_id
        SQL);
        $this->addSql(<<<'SQL'
            COMMENT ON COLUMN divercity.space_highlight.space_id IS NULL
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE divercity.space_highlight ADD CONSTRAINT space_highlight_report_media_object_id_fkey FOREIGN KEY (report_media_object_id) REFERENCES media_object (id) NOT DEFERRABLE INITIALLY IMMEDIATE
        SQL);
        $this->addSql(<<<'SQL'
            CREATE INDEX IDX_4E62BB7328A96488 ON divercity.space_highlight (report_media_object_id)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER INDEX divercity.idx_4e62bb7323575340 RENAME TO idx_space_highlight_space
        SQL);
        $this->addSql(<<<'SQL'
            CREATE INDEX idx_booking_attachment_type ON divercity.booking_attachment (booking_id, type)
        SQL);
        $this->addSql(<<<'SQL'
            CREATE INDEX idx_booking_attachment_media_object ON divercity.booking_attachment (file_object_id)
        SQL);
        $this->addSql(<<<'SQL'
            CREATE SEQUENCE divercity.space_statistic_id_seq
        SQL);
        $this->addSql(<<<'SQL'
            SELECT setval('divercity.space_statistic_id_seq', (SELECT MAX(id) FROM divercity.space_statistic))
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE divercity.space_statistic ALTER id SET DEFAULT nextval('divercity.space_statistic_id_seq')
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE divercity.space_statistic ALTER created_at SET DEFAULT 'now()'
        SQL);
        $this->addSql(<<<'SQL'
            ALTER INDEX divercity.idx_e36125db4b6c6223 RENAME TO idx_space_statistic_highlight
        SQL);
        $this->addSql(<<<'SQL'
            COMMENT ON COLUMN divercity.space.description IS 'Contenu HTML riche (couleur, police, taille...) édité par l''admin, affiché tel quel sur la page d''accueil.'
        SQL);
    }
}
