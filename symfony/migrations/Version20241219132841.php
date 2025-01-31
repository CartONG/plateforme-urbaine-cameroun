<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20241219132841 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SEQUENCE qgis_map_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE qgis_project_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE TABLE qgis_map (id INT NOT NULL, qgis_project_id INT DEFAULT NULL, name VARCHAR(255) NOT NULL, layers JSON DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_25C294DC4577FAF0 ON qgis_map (qgis_project_id)');
        $this->addSql('CREATE TABLE qgis_project (id INT NOT NULL, file_path VARCHAR(255) DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('ALTER TABLE qgis_map ADD CONSTRAINT FK_25C294DC4577FAF0 FOREIGN KEY (qgis_project_id) REFERENCES media_object (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('DROP SEQUENCE qgis_map_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE qgis_project_id_seq CASCADE');
        $this->addSql('ALTER TABLE qgis_map DROP CONSTRAINT FK_25C294DC4577FAF0');
        $this->addSql('DROP TABLE qgis_map');
        $this->addSql('DROP TABLE qgis_project');
    }
}
