<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250218155754 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SEQUENCE administrative_area_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE TABLE administrative_area (id INT NOT NULL, parent_id INT DEFAULT NULL, administrative_scope_id INT DEFAULT NULL, name VARCHAR(255) NOT NULL, name_en VARCHAR(255) NOT NULL, code VARCHAR(50) NOT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, validated_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_C3425085727ACA70 ON administrative_area (parent_id)');
        $this->addSql('CREATE INDEX IDX_C3425085C1892E43 ON administrative_area (administrative_scope_id)');
        $this->addSql('ALTER TABLE administrative_area ADD CONSTRAINT FK_C3425085727ACA70 FOREIGN KEY (parent_id) REFERENCES administrative_area (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE administrative_area ADD CONSTRAINT FK_C3425085C1892E43 FOREIGN KEY (administrative_scope_id) REFERENCES administrative_scope (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('DROP SEQUENCE administrative_area_id_seq CASCADE');
        $this->addSql('ALTER TABLE administrative_area DROP CONSTRAINT FK_C3425085727ACA70');
        $this->addSql('ALTER TABLE administrative_area DROP CONSTRAINT FK_C3425085C1892E43');
        $this->addSql('DROP TABLE administrative_area');
    }
}
