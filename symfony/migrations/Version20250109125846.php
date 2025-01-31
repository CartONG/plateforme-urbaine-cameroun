<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250109125846 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE atlas ADD logo_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE atlas ADD CONSTRAINT FK_720AD60FF98F144A FOREIGN KEY (logo_id) REFERENCES media_object (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_720AD60FF98F144A ON atlas (logo_id)');
        $this->addSql('ALTER TABLE qgis_map ADD logo_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE qgis_map ADD CONSTRAINT FK_25C294DCF98F144A FOREIGN KEY (logo_id) REFERENCES media_object (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_25C294DCF98F144A ON qgis_map (logo_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE atlas DROP CONSTRAINT FK_720AD60FF98F144A');
        $this->addSql('DROP INDEX UNIQ_720AD60FF98F144A');
        $this->addSql('ALTER TABLE atlas DROP logo_id');
        $this->addSql('ALTER TABLE qgis_map DROP CONSTRAINT FK_25C294DCF98F144A');
        $this->addSql('DROP INDEX UNIQ_25C294DCF98F144A');
        $this->addSql('ALTER TABLE qgis_map DROP logo_id');
    }
}
