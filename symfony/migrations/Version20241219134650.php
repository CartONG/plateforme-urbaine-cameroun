<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20241219134650 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE qgis_map DROP CONSTRAINT FK_25C294DC4577FAF0');
        $this->addSql('ALTER TABLE qgis_map ADD CONSTRAINT FK_25C294DC4577FAF0 FOREIGN KEY (qgis_project_id) REFERENCES qgis_project (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE qgis_map DROP CONSTRAINT fk_25c294dc4577faf0');
        $this->addSql('ALTER TABLE qgis_map ADD CONSTRAINT fk_25c294dc4577faf0 FOREIGN KEY (qgis_project_id) REFERENCES media_object (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
    }
}
