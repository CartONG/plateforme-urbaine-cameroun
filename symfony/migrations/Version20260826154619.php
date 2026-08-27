<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20260826154619 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // 1. Ajouter la colonne en nullable d'abord
        $this->addSql(<<<'SQL'
            ALTER TABLE divercity.booking ADD role VARCHAR(150)
        SQL);

        // 2. Backfill des lignes existantes (adaptez la valeur/logique à votre besoin)
        $this->addSql(<<<'SQL'
            UPDATE divercity.booking SET role = 'DEMANDEUR' WHERE role IS NULL
        SQL);

        // 3. Puis seulement, contraindre en NOT NULL
        $this->addSql(<<<'SQL'
            ALTER TABLE divercity.booking ALTER COLUMN role SET NOT NULL
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
            CREATE INDEX idx_booking_attachment_type ON divercity.booking_attachment (booking_id, type)
        SQL);
        $this->addSql(<<<'SQL'
            CREATE INDEX idx_booking_attachment_media_object ON divercity.booking_attachment (file_object_id)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE divercity.booking DROP role
        SQL);
    }
}
