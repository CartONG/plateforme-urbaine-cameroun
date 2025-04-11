<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250326200143 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SEQUENCE admin1_boundaries_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE admin2_boundaries_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE admin3_boundaries_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE TABLE admin1_boundaries (id INT NOT NULL, adm1_name VARCHAR(255) NOT NULL, adm1_pcode VARCHAR(255) NOT NULL, geometry geometry(GEOMETRY, 0) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE TABLE admin2_boundaries (id INT NOT NULL, adm2_name VARCHAR(255) NOT NULL, adm2_pcode VARCHAR(255) NOT NULL, adm1_name VARCHAR(255) NOT NULL, adm1_pcode VARCHAR(255) NOT NULL, geometry geometry(GEOMETRY, 0) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE TABLE admin3_boundaries (id INT NOT NULL, adm3_name VARCHAR(255) NOT NULL, adm3_pcode VARCHAR(255) NOT NULL, adm2_name VARCHAR(255) NOT NULL, adm2_pcode VARCHAR(255) NOT NULL, adm1_name VARCHAR(255) NOT NULL, adm1_pcode VARCHAR(255) NOT NULL, geometry geometry(GEOMETRY, 0) NOT NULL, PRIMARY KEY(id))');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP SEQUENCE admin1_boundaries_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE admin2_boundaries_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE admin3_boundaries_id_seq CASCADE');
        $this->addSql('DROP TABLE admin1_boundaries');
        $this->addSql('DROP TABLE admin2_boundaries');
        $this->addSql('DROP TABLE admin3_boundaries');
    }
}
