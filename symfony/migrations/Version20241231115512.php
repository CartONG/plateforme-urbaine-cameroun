<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20241231115512 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SEQUENCE atlas_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE TABLE atlas (id INT NOT NULL, name VARCHAR(255) NOT NULL, atlas_group VARCHAR(255) NOT NULL, position INT NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE TABLE atlas_qgis_map (atlas_id INT NOT NULL, qgis_map_id INT NOT NULL, PRIMARY KEY(atlas_id, qgis_map_id))');
        $this->addSql('CREATE INDEX IDX_F65392725AAA09F8 ON atlas_qgis_map (atlas_id)');
        $this->addSql('CREATE INDEX IDX_F65392728295E5C3 ON atlas_qgis_map (qgis_map_id)');
        $this->addSql('ALTER TABLE atlas_qgis_map ADD CONSTRAINT FK_F65392725AAA09F8 FOREIGN KEY (atlas_id) REFERENCES atlas (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE atlas_qgis_map ADD CONSTRAINT FK_F65392728295E5C3 FOREIGN KEY (qgis_map_id) REFERENCES qgis_map (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('DROP SEQUENCE atlas_id_seq CASCADE');
        $this->addSql('ALTER TABLE atlas_qgis_map DROP CONSTRAINT FK_F65392725AAA09F8');
        $this->addSql('ALTER TABLE atlas_qgis_map DROP CONSTRAINT FK_F65392728295E5C3');
        $this->addSql('DROP TABLE atlas');
        $this->addSql('DROP TABLE atlas_qgis_map');
    }
}
