<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250218155815 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        $this->addSql("INSERT INTO administrative_area (id, name, name_en, code, created_at, validated_at, administrative_scope_id)
                   VALUES (1, 'Adamaoua', 'Adamaoua', 'CM001', '2018-12-17 00:00:00', '2019-01-04 00:00:00', 2)");
        $this->addSql("INSERT INTO administrative_area (id, name, name_en, code, created_at, validated_at, administrative_scope_id)
                   VALUES (2, 'Centre', 'Centre', 'CM002', '2018-12-17 00:00:00', '2019-01-04 00:00:00', 2)");
        $this->addSql("INSERT INTO administrative_area (id, name, name_en, code, created_at, validated_at, administrative_scope_id)
                   VALUES (3, 'Est', 'East', 'CM003', '2018-12-17 00:00:00', '2019-01-04 00:00:00', 2)");
        $this->addSql("INSERT INTO administrative_area (id, name, name_en, code, created_at, validated_at, administrative_scope_id)
                   VALUES (4, 'Extrême-Nord', 'Far-North', 'CM004', '2018-12-17 00:00:00', '2019-01-04 00:00:00', 2)");
        $this->addSql("INSERT INTO administrative_area (id, name, name_en, code, created_at, validated_at, administrative_scope_id)
                   VALUES (5, 'Littoral', 'Littoral', 'CM005', '2018-12-17 00:00:00', '2019-01-04 00:00:00', 2)");
        $this->addSql("INSERT INTO administrative_area (id, name, name_en, code, created_at, validated_at, administrative_scope_id)
                   VALUES (6, 'Nord', 'North', 'CM006', '2018-12-17 00:00:00', '2019-01-04 00:00:00', 2)");
        $this->addSql("INSERT INTO administrative_area (id, name, name_en, code, created_at, validated_at, administrative_scope_id)
                   VALUES (7, 'Nord-Ouest', 'North-West', 'CM007', '2018-12-17 00:00:00', '2019-01-04 00:00:00', 2)");
        $this->addSql("INSERT INTO administrative_area (id, name, name_en, code, created_at, validated_at, administrative_scope_id)
                   VALUES (8, 'Sud', 'South', 'CM009', '2018-12-17 00:00:00', '2019-01-04 00:00:00', 2)");
        $this->addSql("INSERT INTO administrative_area (id, name, name_en, code, created_at, validated_at, administrative_scope_id)
                   VALUES (9, 'Sud-Ouest', 'South-West', 'CM010', '2018-12-17 00:00:00', '2019-01-04 00:00:00', 2)");
        $this->addSql("INSERT INTO administrative_area (id, name, name_en, code, created_at, validated_at, administrative_scope_id)
                   VALUES (10, 'Ouest', 'West', 'CM008', '2018-12-17 00:00:00', '2019-01-04 00:00:00', 2)");

    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
    }
}
