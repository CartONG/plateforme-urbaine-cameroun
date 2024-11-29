<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20241125155901 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SEQUENCE actor_expertise_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE administrative_scope_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE geo_data_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE media_object_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE organisation_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE thematic_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE "user_id_seq" INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE user_like_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE TABLE actor (id UUID NOT NULL, logo_id INT DEFAULT NULL, created_by INT DEFAULT NULL, updated_by INT DEFAULT NULL, name VARCHAR(255) NOT NULL, acronym VARCHAR(255) NOT NULL, category VARCHAR(255) NOT NULL, description TEXT DEFAULT NULL, office_name VARCHAR(255) DEFAULT NULL, office_address VARCHAR(255) DEFAULT NULL, office_location geometry(POINT, 0) DEFAULT NULL, contact_name VARCHAR(255) DEFAULT NULL, contact_position VARCHAR(255) DEFAULT NULL, website VARCHAR(255) DEFAULT NULL, phone VARCHAR(255) DEFAULT NULL, email VARCHAR(255) DEFAULT NULL, external_images TEXT DEFAULT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, updated_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, slug VARCHAR(128) DEFAULT NULL, is_validated BOOLEAN NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_447556F9989D9B62 ON actor (slug)');
        $this->addSql('CREATE INDEX IDX_447556F9F98F144A ON actor (logo_id)');
        $this->addSql('CREATE INDEX IDX_447556F9DE12AB56 ON actor (created_by)');
        $this->addSql('CREATE INDEX IDX_447556F916FE72E1 ON actor (updated_by)');
        $this->addSql('COMMENT ON COLUMN actor.id IS \'(DC2Type:uuid)\'');
        $this->addSql('COMMENT ON COLUMN actor.external_images IS \'(DC2Type:simple_array)\'');
        $this->addSql('CREATE TABLE actor_actor_expertise (actor_id UUID NOT NULL, actor_expertise_id INT NOT NULL, PRIMARY KEY(actor_id, actor_expertise_id))');
        $this->addSql('CREATE INDEX IDX_4A438EAD10DAF24A ON actor_actor_expertise (actor_id)');
        $this->addSql('CREATE INDEX IDX_4A438EAD916905AC ON actor_actor_expertise (actor_expertise_id)');
        $this->addSql('COMMENT ON COLUMN actor_actor_expertise.actor_id IS \'(DC2Type:uuid)\'');
        $this->addSql('CREATE TABLE actor_thematic (actor_id UUID NOT NULL, thematic_id INT NOT NULL, PRIMARY KEY(actor_id, thematic_id))');
        $this->addSql('CREATE INDEX IDX_D159A26410DAF24A ON actor_thematic (actor_id)');
        $this->addSql('CREATE INDEX IDX_D159A2642395FCED ON actor_thematic (thematic_id)');
        $this->addSql('COMMENT ON COLUMN actor_thematic.actor_id IS \'(DC2Type:uuid)\'');
        $this->addSql('CREATE TABLE actor_administrative_scope (actor_id UUID NOT NULL, administrative_scope_id INT NOT NULL, PRIMARY KEY(actor_id, administrative_scope_id))');
        $this->addSql('CREATE INDEX IDX_65EBE3CC10DAF24A ON actor_administrative_scope (actor_id)');
        $this->addSql('CREATE INDEX IDX_65EBE3CCC1892E43 ON actor_administrative_scope (administrative_scope_id)');
        $this->addSql('COMMENT ON COLUMN actor_administrative_scope.actor_id IS \'(DC2Type:uuid)\'');
        $this->addSql('CREATE TABLE actor_media_object (actor_id UUID NOT NULL, media_object_id INT NOT NULL, PRIMARY KEY(actor_id, media_object_id))');
        $this->addSql('CREATE INDEX IDX_18E6A66710DAF24A ON actor_media_object (actor_id)');
        $this->addSql('CREATE INDEX IDX_18E6A66764DE5A5 ON actor_media_object (media_object_id)');
        $this->addSql('COMMENT ON COLUMN actor_media_object.actor_id IS \'(DC2Type:uuid)\'');
        $this->addSql('CREATE TABLE actor_expertise (id INT NOT NULL, name VARCHAR(255) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE TABLE administrative_scope (id INT NOT NULL, name VARCHAR(255) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE TABLE geo_data (id INT NOT NULL, osm_id BIGINT NOT NULL, osm_type VARCHAR(255) NOT NULL, name VARCHAR(255) NOT NULL, latitude DOUBLE PRECISION DEFAULT NULL, longitude DOUBLE PRECISION DEFAULT NULL, bounds JSON DEFAULT NULL, address JSON DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE TABLE media_object (id INT NOT NULL, file_path VARCHAR(255) DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE TABLE organisation (id INT NOT NULL, actor_id UUID DEFAULT NULL, name VARCHAR(255) NOT NULL, acronym VARCHAR(255) NOT NULL, contracting BOOLEAN DEFAULT false NOT NULL, donor BOOLEAN DEFAULT false NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_E6E132B410DAF24A ON organisation (actor_id)');
        $this->addSql('COMMENT ON COLUMN organisation.actor_id IS \'(DC2Type:uuid)\'');
        $this->addSql('CREATE TABLE project (id UUID NOT NULL, actor_id UUID NOT NULL, contracting_organisation_id INT NOT NULL, created_by INT DEFAULT NULL, updated_by INT DEFAULT NULL, geo_data_id INT DEFAULT NULL, name VARCHAR(255) NOT NULL, status VARCHAR(255) NOT NULL, description TEXT DEFAULT NULL, images JSON DEFAULT NULL, partners JSON DEFAULT NULL, intervention_zone VARCHAR(255) NOT NULL, focal_point_name VARCHAR(255) NOT NULL, focal_point_position VARCHAR(255) DEFAULT NULL, focal_point_email VARCHAR(255) DEFAULT NULL, focal_point_tel VARCHAR(255) DEFAULT NULL, focal_point_photo VARCHAR(255) DEFAULT NULL, website VARCHAR(255) DEFAULT NULL, logo VARCHAR(255) DEFAULT NULL, deliverables TEXT DEFAULT NULL, calendar TEXT DEFAULT NULL, beneficiary_types JSON DEFAULT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, updated_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, slug VARCHAR(128) DEFAULT NULL, is_validated BOOLEAN NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_2FB3D0EE989D9B62 ON project (slug)');
        $this->addSql('CREATE INDEX IDX_2FB3D0EE10DAF24A ON project (actor_id)');
        $this->addSql('CREATE INDEX IDX_2FB3D0EEC38A4C7D ON project (contracting_organisation_id)');
        $this->addSql('CREATE INDEX IDX_2FB3D0EEDE12AB56 ON project (created_by)');
        $this->addSql('CREATE INDEX IDX_2FB3D0EE16FE72E1 ON project (updated_by)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_2FB3D0EE80E32C3E ON project (geo_data_id)');
        $this->addSql('COMMENT ON COLUMN project.id IS \'(DC2Type:uuid)\'');
        $this->addSql('COMMENT ON COLUMN project.actor_id IS \'(DC2Type:uuid)\'');
        $this->addSql('CREATE TABLE project_thematic (project_id UUID NOT NULL, thematic_id INT NOT NULL, PRIMARY KEY(project_id, thematic_id))');
        $this->addSql('CREATE INDEX IDX_415254A9166D1F9C ON project_thematic (project_id)');
        $this->addSql('CREATE INDEX IDX_415254A92395FCED ON project_thematic (thematic_id)');
        $this->addSql('COMMENT ON COLUMN project_thematic.project_id IS \'(DC2Type:uuid)\'');
        $this->addSql('CREATE TABLE projects_donors (project_id UUID NOT NULL, organisation_id INT NOT NULL, PRIMARY KEY(project_id, organisation_id))');
        $this->addSql('CREATE INDEX IDX_B446A753166D1F9C ON projects_donors (project_id)');
        $this->addSql('CREATE INDEX IDX_B446A7539E6B1585 ON projects_donors (organisation_id)');
        $this->addSql('COMMENT ON COLUMN projects_donors.project_id IS \'(DC2Type:uuid)\'');
        $this->addSql('CREATE TABLE resource (id UUID NOT NULL, created_by INT DEFAULT NULL, updated_by INT DEFAULT NULL, name VARCHAR(255) NOT NULL, description TEXT NOT NULL, type VARCHAR(255) NOT NULL, link VARCHAR(255) DEFAULT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, updated_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, is_validated BOOLEAN NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_BC91F416DE12AB56 ON resource (created_by)');
        $this->addSql('CREATE INDEX IDX_BC91F41616FE72E1 ON resource (updated_by)');
        $this->addSql('COMMENT ON COLUMN resource.id IS \'(DC2Type:uuid)\'');
        $this->addSql('CREATE TABLE thematic (id INT NOT NULL, name VARCHAR(255) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE TABLE "user" (id INT NOT NULL, logo_id INT DEFAULT NULL, first_name VARCHAR(255) NOT NULL, last_name VARCHAR(255) NOT NULL, email VARCHAR(180) NOT NULL, roles JSON NOT NULL, password VARCHAR(255) DEFAULT NULL, requested_roles JSON DEFAULT NULL, organisation VARCHAR(255) DEFAULT NULL, position VARCHAR(255) DEFAULT NULL, phone VARCHAR(20) DEFAULT NULL, sign_up_message TEXT DEFAULT NULL, description TEXT DEFAULT NULL, is_validated BOOLEAN NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_8D93D649F98F144A ON "user" (logo_id)');
        $this->addSql('CREATE TABLE user_like (id INT NOT NULL, user_id_id INT NOT NULL, content_id UUID NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_D6E20C7A9D86650F ON user_like (user_id_id)');
        $this->addSql('CREATE UNIQUE INDEX unique_like_idx ON user_like (user_id_id, content_id)');
        $this->addSql('COMMENT ON COLUMN user_like.content_id IS \'(DC2Type:uuid)\'');
        $this->addSql('ALTER TABLE actor ADD CONSTRAINT FK_447556F9F98F144A FOREIGN KEY (logo_id) REFERENCES media_object (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE actor ADD CONSTRAINT FK_447556F9DE12AB56 FOREIGN KEY (created_by) REFERENCES "user" (id) ON DELETE SET NULL NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE actor ADD CONSTRAINT FK_447556F916FE72E1 FOREIGN KEY (updated_by) REFERENCES "user" (id) ON DELETE SET NULL NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE actor_actor_expertise ADD CONSTRAINT FK_4A438EAD10DAF24A FOREIGN KEY (actor_id) REFERENCES actor (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE actor_actor_expertise ADD CONSTRAINT FK_4A438EAD916905AC FOREIGN KEY (actor_expertise_id) REFERENCES actor_expertise (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE actor_thematic ADD CONSTRAINT FK_D159A26410DAF24A FOREIGN KEY (actor_id) REFERENCES actor (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE actor_thematic ADD CONSTRAINT FK_D159A2642395FCED FOREIGN KEY (thematic_id) REFERENCES thematic (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE actor_administrative_scope ADD CONSTRAINT FK_65EBE3CC10DAF24A FOREIGN KEY (actor_id) REFERENCES actor (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE actor_administrative_scope ADD CONSTRAINT FK_65EBE3CCC1892E43 FOREIGN KEY (administrative_scope_id) REFERENCES administrative_scope (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE actor_media_object ADD CONSTRAINT FK_18E6A66710DAF24A FOREIGN KEY (actor_id) REFERENCES actor (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE actor_media_object ADD CONSTRAINT FK_18E6A66764DE5A5 FOREIGN KEY (media_object_id) REFERENCES media_object (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE organisation ADD CONSTRAINT FK_E6E132B410DAF24A FOREIGN KEY (actor_id) REFERENCES actor (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE project ADD CONSTRAINT FK_2FB3D0EE10DAF24A FOREIGN KEY (actor_id) REFERENCES actor (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE project ADD CONSTRAINT FK_2FB3D0EEC38A4C7D FOREIGN KEY (contracting_organisation_id) REFERENCES organisation (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE project ADD CONSTRAINT FK_2FB3D0EEDE12AB56 FOREIGN KEY (created_by) REFERENCES "user" (id) ON DELETE SET NULL NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE project ADD CONSTRAINT FK_2FB3D0EE16FE72E1 FOREIGN KEY (updated_by) REFERENCES "user" (id) ON DELETE SET NULL NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE project ADD CONSTRAINT FK_2FB3D0EE80E32C3E FOREIGN KEY (geo_data_id) REFERENCES geo_data (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE project_thematic ADD CONSTRAINT FK_415254A9166D1F9C FOREIGN KEY (project_id) REFERENCES project (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE project_thematic ADD CONSTRAINT FK_415254A92395FCED FOREIGN KEY (thematic_id) REFERENCES thematic (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE projects_donors ADD CONSTRAINT FK_B446A753166D1F9C FOREIGN KEY (project_id) REFERENCES project (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE projects_donors ADD CONSTRAINT FK_B446A7539E6B1585 FOREIGN KEY (organisation_id) REFERENCES organisation (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE resource ADD CONSTRAINT FK_BC91F416DE12AB56 FOREIGN KEY (created_by) REFERENCES "user" (id) ON DELETE SET NULL NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE resource ADD CONSTRAINT FK_BC91F41616FE72E1 FOREIGN KEY (updated_by) REFERENCES "user" (id) ON DELETE SET NULL NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE "user" ADD CONSTRAINT FK_8D93D649F98F144A FOREIGN KEY (logo_id) REFERENCES media_object (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE user_like ADD CONSTRAINT FK_D6E20C7A9D86650F FOREIGN KEY (user_id_id) REFERENCES "user" (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('DROP SEQUENCE actor_expertise_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE administrative_scope_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE geo_data_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE media_object_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE organisation_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE thematic_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE "user_id_seq" CASCADE');
        $this->addSql('DROP SEQUENCE user_like_id_seq CASCADE');
        $this->addSql('ALTER TABLE actor DROP CONSTRAINT FK_447556F9F98F144A');
        $this->addSql('ALTER TABLE actor DROP CONSTRAINT FK_447556F9DE12AB56');
        $this->addSql('ALTER TABLE actor DROP CONSTRAINT FK_447556F916FE72E1');
        $this->addSql('ALTER TABLE actor_actor_expertise DROP CONSTRAINT FK_4A438EAD10DAF24A');
        $this->addSql('ALTER TABLE actor_actor_expertise DROP CONSTRAINT FK_4A438EAD916905AC');
        $this->addSql('ALTER TABLE actor_thematic DROP CONSTRAINT FK_D159A26410DAF24A');
        $this->addSql('ALTER TABLE actor_thematic DROP CONSTRAINT FK_D159A2642395FCED');
        $this->addSql('ALTER TABLE actor_administrative_scope DROP CONSTRAINT FK_65EBE3CC10DAF24A');
        $this->addSql('ALTER TABLE actor_administrative_scope DROP CONSTRAINT FK_65EBE3CCC1892E43');
        $this->addSql('ALTER TABLE actor_media_object DROP CONSTRAINT FK_18E6A66710DAF24A');
        $this->addSql('ALTER TABLE actor_media_object DROP CONSTRAINT FK_18E6A66764DE5A5');
        $this->addSql('ALTER TABLE organisation DROP CONSTRAINT FK_E6E132B410DAF24A');
        $this->addSql('ALTER TABLE project DROP CONSTRAINT FK_2FB3D0EE10DAF24A');
        $this->addSql('ALTER TABLE project DROP CONSTRAINT FK_2FB3D0EEC38A4C7D');
        $this->addSql('ALTER TABLE project DROP CONSTRAINT FK_2FB3D0EEDE12AB56');
        $this->addSql('ALTER TABLE project DROP CONSTRAINT FK_2FB3D0EE16FE72E1');
        $this->addSql('ALTER TABLE project DROP CONSTRAINT FK_2FB3D0EE80E32C3E');
        $this->addSql('ALTER TABLE project_thematic DROP CONSTRAINT FK_415254A9166D1F9C');
        $this->addSql('ALTER TABLE project_thematic DROP CONSTRAINT FK_415254A92395FCED');
        $this->addSql('ALTER TABLE projects_donors DROP CONSTRAINT FK_B446A753166D1F9C');
        $this->addSql('ALTER TABLE projects_donors DROP CONSTRAINT FK_B446A7539E6B1585');
        $this->addSql('ALTER TABLE resource DROP CONSTRAINT FK_BC91F416DE12AB56');
        $this->addSql('ALTER TABLE resource DROP CONSTRAINT FK_BC91F41616FE72E1');
        $this->addSql('ALTER TABLE "user" DROP CONSTRAINT FK_8D93D649F98F144A');
        $this->addSql('ALTER TABLE user_like DROP CONSTRAINT FK_D6E20C7A9D86650F');
        $this->addSql('DROP TABLE actor');
        $this->addSql('DROP TABLE actor_actor_expertise');
        $this->addSql('DROP TABLE actor_thematic');
        $this->addSql('DROP TABLE actor_administrative_scope');
        $this->addSql('DROP TABLE actor_media_object');
        $this->addSql('DROP TABLE actor_expertise');
        $this->addSql('DROP TABLE administrative_scope');
        $this->addSql('DROP TABLE geo_data');
        $this->addSql('DROP TABLE media_object');
        $this->addSql('DROP TABLE organisation');
        $this->addSql('DROP TABLE project');
        $this->addSql('DROP TABLE project_thematic');
        $this->addSql('DROP TABLE projects_donors');
        $this->addSql('DROP TABLE resource');
        $this->addSql('DROP TABLE thematic');
        $this->addSql('DROP TABLE "user"');
        $this->addSql('DROP TABLE user_like');
    }
}
