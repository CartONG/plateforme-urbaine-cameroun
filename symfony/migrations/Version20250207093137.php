<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250207093137 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE actor_media_object DROP CONSTRAINT fk_18e6a66764de5a5');
        $this->addSql('ALTER TABLE actor DROP CONSTRAINT fk_447556f9f98f144a');
        $this->addSql('ALTER TABLE resource DROP CONSTRAINT fk_bc91f41693cb796c');
        $this->addSql('ALTER TABLE qgis_map DROP CONSTRAINT fk_25c294dcf98f144a');
        $this->addSql('ALTER TABLE atlas DROP CONSTRAINT fk_720ad60ff98f144a');
        $this->addSql('ALTER TABLE "user" DROP CONSTRAINT fk_8d93d649f98f144a');
        $this->addSql('DROP SEQUENCE media_object_id_seq CASCADE');
        $this->addSql('CREATE SEQUENCE file_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE TABLE file (id INT NOT NULL, file_path VARCHAR(255) DEFAULT NULL, original_name VARCHAR(255) DEFAULT NULL, mime_type VARCHAR(255) DEFAULT NULL, dimensions JSON DEFAULT NULL, size INT DEFAULT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, updated_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, type VARCHAR(255) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('DROP TABLE media_object');
        $this->addSql('ALTER TABLE actor ADD CONSTRAINT FK_447556F9F98F144A FOREIGN KEY (logo_id) REFERENCES file (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE actor_media_object ADD CONSTRAINT FK_18E6A66764DE5A5 FOREIGN KEY (media_object_id) REFERENCES file (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE atlas ADD CONSTRAINT FK_720AD60FF98F144A FOREIGN KEY (logo_id) REFERENCES file (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE qgis_map ADD CONSTRAINT FK_25C294DCF98F144A FOREIGN KEY (logo_id) REFERENCES file (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE resource ADD CONSTRAINT FK_BC91F41693CB796C FOREIGN KEY (file_id) REFERENCES file (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE "user" ADD CONSTRAINT FK_8D93D649F98F144A FOREIGN KEY (logo_id) REFERENCES file (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE actor DROP CONSTRAINT FK_447556F9F98F144A');
        $this->addSql('ALTER TABLE actor_media_object DROP CONSTRAINT FK_18E6A66764DE5A5');
        $this->addSql('ALTER TABLE atlas DROP CONSTRAINT FK_720AD60FF98F144A');
        $this->addSql('ALTER TABLE qgis_map DROP CONSTRAINT FK_25C294DCF98F144A');
        $this->addSql('ALTER TABLE resource DROP CONSTRAINT FK_BC91F41693CB796C');
        $this->addSql('ALTER TABLE "user" DROP CONSTRAINT FK_8D93D649F98F144A');
        $this->addSql('DROP SEQUENCE file_id_seq CASCADE');
        $this->addSql('CREATE SEQUENCE media_object_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE TABLE media_object (id INT NOT NULL, file_path VARCHAR(255) DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('DROP TABLE file');
        $this->addSql('ALTER TABLE actor_media_object DROP CONSTRAINT fk_18e6a66764de5a5');
        $this->addSql('ALTER TABLE actor_media_object ADD CONSTRAINT fk_18e6a66764de5a5 FOREIGN KEY (media_object_id) REFERENCES media_object (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE actor DROP CONSTRAINT fk_447556f9f98f144a');
        $this->addSql('ALTER TABLE actor ADD CONSTRAINT fk_447556f9f98f144a FOREIGN KEY (logo_id) REFERENCES media_object (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE resource DROP CONSTRAINT fk_bc91f41693cb796c');
        $this->addSql('ALTER TABLE resource ADD CONSTRAINT fk_bc91f41693cb796c FOREIGN KEY (file_id) REFERENCES media_object (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE qgis_map DROP CONSTRAINT fk_25c294dcf98f144a');
        $this->addSql('ALTER TABLE qgis_map ADD CONSTRAINT fk_25c294dcf98f144a FOREIGN KEY (logo_id) REFERENCES media_object (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE atlas DROP CONSTRAINT fk_720ad60ff98f144a');
        $this->addSql('ALTER TABLE atlas ADD CONSTRAINT fk_720ad60ff98f144a FOREIGN KEY (logo_id) REFERENCES media_object (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE "user" DROP CONSTRAINT fk_8d93d649f98f144a');
        $this->addSql('ALTER TABLE "user" ADD CONSTRAINT fk_8d93d649f98f144a FOREIGN KEY (logo_id) REFERENCES media_object (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
    }
}
