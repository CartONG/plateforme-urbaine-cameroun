<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20241008150316 extends AbstractMigration
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
        $this->addSql('CREATE SEQUENCE project_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE thematic_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE "user_id_seq" INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE TABLE actor (id UUID NOT NULL, created_by_id INT NOT NULL, name VARCHAR(255) NOT NULL, acronym VARCHAR(255) NOT NULL, is_validated BOOLEAN NOT NULL, category VARCHAR(255) NOT NULL, description TEXT NOT NULL, office_name VARCHAR(255) DEFAULT NULL, office_address VARCHAR(255) DEFAULT NULL, contact_name VARCHAR(255) DEFAULT NULL, contact_position VARCHAR(255) DEFAULT NULL, website VARCHAR(255) DEFAULT NULL, phone VARCHAR(255) DEFAULT NULL, email VARCHAR(255) DEFAULT NULL, logo VARCHAR(255) DEFAULT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, updated_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_447556F9B03A8386 ON actor (created_by_id)');
        $this->addSql('COMMENT ON COLUMN actor.id IS \'(DC2Type:uuid)\'');
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
        $this->addSql('CREATE TABLE actor_expertise (id INT NOT NULL, name VARCHAR(255) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE TABLE administrative_scope (id INT NOT NULL, name VARCHAR(255) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE TABLE project (id INT NOT NULL, actor_id UUID NOT NULL, title VARCHAR(255) NOT NULL, location VARCHAR(255) NOT NULL, coords geometry(POINT, 0) NOT NULL, status VARCHAR(255) NOT NULL, description TEXT DEFAULT NULL, images JSON DEFAULT NULL, partners JSON DEFAULT NULL, intervention_zone VARCHAR(255) NOT NULL, project_manager_name VARCHAR(255) DEFAULT NULL, project_manager_position VARCHAR(255) DEFAULT NULL, project_manager_email VARCHAR(255) DEFAULT NULL, project_manager_tel VARCHAR(255) DEFAULT NULL, project_manager_photo VARCHAR(255) DEFAULT NULL, website VARCHAR(255) DEFAULT NULL, logo VARCHAR(255) DEFAULT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, updated_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_2FB3D0EE10DAF24A ON project (actor_id)');
        $this->addSql('COMMENT ON COLUMN project.actor_id IS \'(DC2Type:uuid)\'');
        $this->addSql('CREATE TABLE project_thematic (project_id INT NOT NULL, thematic_id INT NOT NULL, PRIMARY KEY(project_id, thematic_id))');
        $this->addSql('CREATE INDEX IDX_415254A9166D1F9C ON project_thematic (project_id)');
        $this->addSql('CREATE INDEX IDX_415254A92395FCED ON project_thematic (thematic_id)');
        $this->addSql('CREATE TABLE financed_projects_actors (project_id INT NOT NULL, actor_id UUID NOT NULL, PRIMARY KEY(project_id, actor_id))');
        $this->addSql('CREATE INDEX IDX_50C6B8EC166D1F9C ON financed_projects_actors (project_id)');
        $this->addSql('CREATE INDEX IDX_50C6B8EC10DAF24A ON financed_projects_actors (actor_id)');
        $this->addSql('COMMENT ON COLUMN financed_projects_actors.actor_id IS \'(DC2Type:uuid)\'');
        $this->addSql('CREATE TABLE contracted_projects_actors (project_id INT NOT NULL, actor_id UUID NOT NULL, PRIMARY KEY(project_id, actor_id))');
        $this->addSql('CREATE INDEX IDX_E73AB790166D1F9C ON contracted_projects_actors (project_id)');
        $this->addSql('CREATE INDEX IDX_E73AB79010DAF24A ON contracted_projects_actors (actor_id)');
        $this->addSql('COMMENT ON COLUMN contracted_projects_actors.actor_id IS \'(DC2Type:uuid)\'');
        $this->addSql('CREATE TABLE thematic (id INT NOT NULL, name VARCHAR(255) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE TABLE "user" (id INT NOT NULL, first_name VARCHAR(255) NOT NULL, last_name VARCHAR(255) NOT NULL, email VARCHAR(180) NOT NULL, roles JSON NOT NULL, password VARCHAR(255) NOT NULL, is_validated BOOLEAN NOT NULL, requested_roles JSON DEFAULT NULL, organisation VARCHAR(255) DEFAULT NULL, position VARCHAR(255) DEFAULT NULL, phone VARCHAR(20) DEFAULT NULL, sign_up_message TEXT DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('ALTER TABLE actor ADD CONSTRAINT FK_447556F9B03A8386 FOREIGN KEY (created_by_id) REFERENCES "user" (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE actor_actor_expertise ADD CONSTRAINT FK_4A438EAD10DAF24A FOREIGN KEY (actor_id) REFERENCES actor (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE actor_actor_expertise ADD CONSTRAINT FK_4A438EAD916905AC FOREIGN KEY (actor_expertise_id) REFERENCES actor_expertise (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE actor_thematic ADD CONSTRAINT FK_D159A26410DAF24A FOREIGN KEY (actor_id) REFERENCES actor (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE actor_thematic ADD CONSTRAINT FK_D159A2642395FCED FOREIGN KEY (thematic_id) REFERENCES thematic (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE actor_administrative_scope ADD CONSTRAINT FK_65EBE3CC10DAF24A FOREIGN KEY (actor_id) REFERENCES actor (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE actor_administrative_scope ADD CONSTRAINT FK_65EBE3CCC1892E43 FOREIGN KEY (administrative_scope_id) REFERENCES administrative_scope (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE project ADD CONSTRAINT FK_2FB3D0EE10DAF24A FOREIGN KEY (actor_id) REFERENCES actor (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE project_thematic ADD CONSTRAINT FK_415254A9166D1F9C FOREIGN KEY (project_id) REFERENCES project (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE project_thematic ADD CONSTRAINT FK_415254A92395FCED FOREIGN KEY (thematic_id) REFERENCES thematic (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE financed_projects_actors ADD CONSTRAINT FK_50C6B8EC166D1F9C FOREIGN KEY (project_id) REFERENCES project (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE financed_projects_actors ADD CONSTRAINT FK_50C6B8EC10DAF24A FOREIGN KEY (actor_id) REFERENCES actor (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE contracted_projects_actors ADD CONSTRAINT FK_E73AB790166D1F9C FOREIGN KEY (project_id) REFERENCES project (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE contracted_projects_actors ADD CONSTRAINT FK_E73AB79010DAF24A FOREIGN KEY (actor_id) REFERENCES actor (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('DROP SEQUENCE actor_expertise_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE administrative_scope_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE project_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE thematic_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE "user_id_seq" CASCADE');
        $this->addSql('ALTER TABLE actor DROP CONSTRAINT FK_447556F9B03A8386');
        $this->addSql('ALTER TABLE actor_actor_expertise DROP CONSTRAINT FK_4A438EAD10DAF24A');
        $this->addSql('ALTER TABLE actor_actor_expertise DROP CONSTRAINT FK_4A438EAD916905AC');
        $this->addSql('ALTER TABLE actor_thematic DROP CONSTRAINT FK_D159A26410DAF24A');
        $this->addSql('ALTER TABLE actor_thematic DROP CONSTRAINT FK_D159A2642395FCED');
        $this->addSql('ALTER TABLE actor_administrative_scope DROP CONSTRAINT FK_65EBE3CC10DAF24A');
        $this->addSql('ALTER TABLE actor_administrative_scope DROP CONSTRAINT FK_65EBE3CCC1892E43');
        $this->addSql('ALTER TABLE project DROP CONSTRAINT FK_2FB3D0EE10DAF24A');
        $this->addSql('ALTER TABLE project_thematic DROP CONSTRAINT FK_415254A9166D1F9C');
        $this->addSql('ALTER TABLE project_thematic DROP CONSTRAINT FK_415254A92395FCED');
        $this->addSql('ALTER TABLE financed_projects_actors DROP CONSTRAINT FK_50C6B8EC166D1F9C');
        $this->addSql('ALTER TABLE financed_projects_actors DROP CONSTRAINT FK_50C6B8EC10DAF24A');
        $this->addSql('ALTER TABLE contracted_projects_actors DROP CONSTRAINT FK_E73AB790166D1F9C');
        $this->addSql('ALTER TABLE contracted_projects_actors DROP CONSTRAINT FK_E73AB79010DAF24A');
        $this->addSql('DROP TABLE actor');
        $this->addSql('DROP TABLE actor_actor_expertise');
        $this->addSql('DROP TABLE actor_thematic');
        $this->addSql('DROP TABLE actor_administrative_scope');
        $this->addSql('DROP TABLE actor_expertise');
        $this->addSql('DROP TABLE administrative_scope');
        $this->addSql('DROP TABLE project');
        $this->addSql('DROP TABLE project_thematic');
        $this->addSql('DROP TABLE financed_projects_actors');
        $this->addSql('DROP TABLE contracted_projects_actors');
        $this->addSql('DROP TABLE thematic');
        $this->addSql('DROP TABLE "user"');
    }
}
