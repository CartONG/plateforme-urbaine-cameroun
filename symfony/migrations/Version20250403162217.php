<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250403162217 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            DROP SEQUENCE admin1_boundaries_id_seq CASCADE
        SQL);
        $this->addSql(<<<'SQL'
            DROP SEQUENCE admin2_boundaries_id_seq CASCADE
        SQL);
        $this->addSql(<<<'SQL'
            DROP SEQUENCE admin3_boundaries_id_seq CASCADE
        SQL);
        $this->addSql(<<<'SQL'
            CREATE SEQUENCE admin1_boundary_id_seq INCREMENT BY 1 MINVALUE 1 START 1
        SQL);
        $this->addSql(<<<'SQL'
            CREATE SEQUENCE admin2_boundary_id_seq INCREMENT BY 1 MINVALUE 1 START 1
        SQL);
        $this->addSql(<<<'SQL'
            CREATE SEQUENCE admin3_boundary_id_seq INCREMENT BY 1 MINVALUE 1 START 1
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE actor_admin1_boundary (actor_id UUID NOT NULL, admin1_boundary_id INT NOT NULL, PRIMARY KEY(actor_id, admin1_boundary_id))
        SQL);
        $this->addSql(<<<'SQL'
            CREATE INDEX IDX_AAC7D7C910DAF24A ON actor_admin1_boundary (actor_id)
        SQL);
        $this->addSql(<<<'SQL'
            CREATE INDEX IDX_AAC7D7C95F1BC794 ON actor_admin1_boundary (admin1_boundary_id)
        SQL);
        $this->addSql(<<<'SQL'
            COMMENT ON COLUMN actor_admin1_boundary.actor_id IS '(DC2Type:uuid)'
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE actor_admin2_boundary (actor_id UUID NOT NULL, admin2_boundary_id INT NOT NULL, PRIMARY KEY(actor_id, admin2_boundary_id))
        SQL);
        $this->addSql(<<<'SQL'
            CREATE INDEX IDX_41F06CCA10DAF24A ON actor_admin2_boundary (actor_id)
        SQL);
        $this->addSql(<<<'SQL'
            CREATE INDEX IDX_41F06CCAE2D1AB5A ON actor_admin2_boundary (admin2_boundary_id)
        SQL);
        $this->addSql(<<<'SQL'
            COMMENT ON COLUMN actor_admin2_boundary.actor_id IS '(DC2Type:uuid)'
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE actor_admin3_boundary (actor_id UUID NOT NULL, admin3_boundary_id INT NOT NULL, PRIMARY KEY(actor_id, admin3_boundary_id))
        SQL);
        $this->addSql(<<<'SQL'
            CREATE INDEX IDX_AE3207F410DAF24A ON actor_admin3_boundary (actor_id)
        SQL);
        $this->addSql(<<<'SQL'
            CREATE INDEX IDX_AE3207F43F4772DF ON actor_admin3_boundary (admin3_boundary_id)
        SQL);
        $this->addSql(<<<'SQL'
            COMMENT ON COLUMN actor_admin3_boundary.actor_id IS '(DC2Type:uuid)'
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE admin1_boundary (id INT NOT NULL, adm1_name VARCHAR(255) NOT NULL, adm1_pcode VARCHAR(255) NOT NULL, geometry geometry(GEOMETRY, 0) NOT NULL, PRIMARY KEY(id))
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE admin2_boundary (id INT NOT NULL, adm2_name VARCHAR(255) NOT NULL, adm2_pcode VARCHAR(255) NOT NULL, adm1_name VARCHAR(255) NOT NULL, adm1_pcode VARCHAR(255) NOT NULL, geometry geometry(GEOMETRY, 0) NOT NULL, PRIMARY KEY(id))
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE admin3_boundary (id INT NOT NULL, adm3_name VARCHAR(255) NOT NULL, adm3_pcode VARCHAR(255) NOT NULL, adm2_name VARCHAR(255) NOT NULL, adm2_pcode VARCHAR(255) NOT NULL, adm1_name VARCHAR(255) NOT NULL, adm1_pcode VARCHAR(255) NOT NULL, geometry geometry(GEOMETRY, 0) NOT NULL, PRIMARY KEY(id))
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE project_admin1_boundary (project_id UUID NOT NULL, admin1_boundary_id INT NOT NULL, PRIMARY KEY(project_id, admin1_boundary_id))
        SQL);
        $this->addSql(<<<'SQL'
            CREATE INDEX IDX_E560333166D1F9C ON project_admin1_boundary (project_id)
        SQL);
        $this->addSql(<<<'SQL'
            CREATE INDEX IDX_E5603335F1BC794 ON project_admin1_boundary (admin1_boundary_id)
        SQL);
        $this->addSql(<<<'SQL'
            COMMENT ON COLUMN project_admin1_boundary.project_id IS '(DC2Type:uuid)'
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE project_admin2_boundary (project_id UUID NOT NULL, admin2_boundary_id INT NOT NULL, PRIMARY KEY(project_id, admin2_boundary_id))
        SQL);
        $this->addSql(<<<'SQL'
            CREATE INDEX IDX_E561B830166D1F9C ON project_admin2_boundary (project_id)
        SQL);
        $this->addSql(<<<'SQL'
            CREATE INDEX IDX_E561B830E2D1AB5A ON project_admin2_boundary (admin2_boundary_id)
        SQL);
        $this->addSql(<<<'SQL'
            COMMENT ON COLUMN project_admin2_boundary.project_id IS '(DC2Type:uuid)'
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE project_admin3_boundary (project_id UUID NOT NULL, admin3_boundary_id INT NOT NULL, PRIMARY KEY(project_id, admin3_boundary_id))
        SQL);
        $this->addSql(<<<'SQL'
            CREATE INDEX IDX_AA3D30E166D1F9C ON project_admin3_boundary (project_id)
        SQL);
        $this->addSql(<<<'SQL'
            CREATE INDEX IDX_AA3D30E3F4772DF ON project_admin3_boundary (admin3_boundary_id)
        SQL);
        $this->addSql(<<<'SQL'
            COMMENT ON COLUMN project_admin3_boundary.project_id IS '(DC2Type:uuid)'
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE actor_admin1_boundary ADD CONSTRAINT FK_AAC7D7C910DAF24A FOREIGN KEY (actor_id) REFERENCES actor (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE actor_admin1_boundary ADD CONSTRAINT FK_AAC7D7C95F1BC794 FOREIGN KEY (admin1_boundary_id) REFERENCES admin1_boundary (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE actor_admin2_boundary ADD CONSTRAINT FK_41F06CCA10DAF24A FOREIGN KEY (actor_id) REFERENCES actor (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE actor_admin2_boundary ADD CONSTRAINT FK_41F06CCAE2D1AB5A FOREIGN KEY (admin2_boundary_id) REFERENCES admin2_boundary (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE actor_admin3_boundary ADD CONSTRAINT FK_AE3207F410DAF24A FOREIGN KEY (actor_id) REFERENCES actor (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE actor_admin3_boundary ADD CONSTRAINT FK_AE3207F43F4772DF FOREIGN KEY (admin3_boundary_id) REFERENCES admin3_boundary (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE project_admin1_boundary ADD CONSTRAINT FK_E560333166D1F9C FOREIGN KEY (project_id) REFERENCES project (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE project_admin1_boundary ADD CONSTRAINT FK_E5603335F1BC794 FOREIGN KEY (admin1_boundary_id) REFERENCES admin1_boundary (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE project_admin2_boundary ADD CONSTRAINT FK_E561B830166D1F9C FOREIGN KEY (project_id) REFERENCES project (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE project_admin2_boundary ADD CONSTRAINT FK_E561B830E2D1AB5A FOREIGN KEY (admin2_boundary_id) REFERENCES admin2_boundary (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE project_admin3_boundary ADD CONSTRAINT FK_AA3D30E166D1F9C FOREIGN KEY (project_id) REFERENCES project (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE project_admin3_boundary ADD CONSTRAINT FK_AA3D30E3F4772DF FOREIGN KEY (admin3_boundary_id) REFERENCES admin3_boundary (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE project_admin2_boundaries DROP CONSTRAINT fk_9685e6c7166d1f9c
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE project_admin2_boundaries DROP CONSTRAINT fk_9685e6c741b45509
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE project_admin1_boundaries DROP CONSTRAINT fk_e11b3437166d1f9c
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE project_admin1_boundaries DROP CONSTRAINT fk_e11b343750c93f70
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE actor_admin3_boundaries DROP CONSTRAINT fk_62cd11c310daf24a
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE actor_admin3_boundaries DROP CONSTRAINT fk_62cd11c3f84f8ee1
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE actor_admin2_boundaries DROP CONSTRAINT fk_f9685dac10daf24a
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE actor_admin2_boundaries DROP CONSTRAINT fk_f9685dac41b45509
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE actor_admin1_boundaries DROP CONSTRAINT fk_8ef68f5c10daf24a
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE actor_admin1_boundaries DROP CONSTRAINT fk_8ef68f5c50c93f70
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE project_admin3_boundaries DROP CONSTRAINT fk_d20aaa8166d1f9c
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE project_admin3_boundaries DROP CONSTRAINT fk_d20aaa8f84f8ee1
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE project_admin2_boundaries
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE project_admin1_boundaries
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE admin2_boundaries
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE admin3_boundaries
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE actor_admin3_boundaries
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE actor_admin2_boundaries
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE actor_admin1_boundaries
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE admin1_boundaries
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE project_admin3_boundaries
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE actor DROP office_location
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE media_object DROP CONSTRAINT fk_14d43132de12ab56
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE media_object DROP CONSTRAINT fk_14d4313216fe72e1
        SQL);
        $this->addSql(<<<'SQL'
            DROP INDEX idx_14d4313216fe72e1
        SQL);
        $this->addSql(<<<'SQL'
            DROP INDEX idx_14d43132de12ab56
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE media_object DROP created_by
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE media_object DROP updated_by
        SQL);
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            CREATE SCHEMA public
        SQL);
        $this->addSql(<<<'SQL'
            DROP SEQUENCE admin1_boundary_id_seq CASCADE
        SQL);
        $this->addSql(<<<'SQL'
            DROP SEQUENCE admin2_boundary_id_seq CASCADE
        SQL);
        $this->addSql(<<<'SQL'
            DROP SEQUENCE admin3_boundary_id_seq CASCADE
        SQL);
        $this->addSql(<<<'SQL'
            CREATE SEQUENCE admin1_boundaries_id_seq INCREMENT BY 1 MINVALUE 1 START 1
        SQL);
        $this->addSql(<<<'SQL'
            CREATE SEQUENCE admin2_boundaries_id_seq INCREMENT BY 1 MINVALUE 1 START 1
        SQL);
        $this->addSql(<<<'SQL'
            CREATE SEQUENCE admin3_boundaries_id_seq INCREMENT BY 1 MINVALUE 1 START 1
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE project_admin2_boundaries (project_id UUID NOT NULL, admin2_boundaries_id INT NOT NULL, PRIMARY KEY(project_id, admin2_boundaries_id))
        SQL);
        $this->addSql(<<<'SQL'
            CREATE INDEX idx_9685e6c741b45509 ON project_admin2_boundaries (admin2_boundaries_id)
        SQL);
        $this->addSql(<<<'SQL'
            CREATE INDEX idx_9685e6c7166d1f9c ON project_admin2_boundaries (project_id)
        SQL);
        $this->addSql(<<<'SQL'
            COMMENT ON COLUMN project_admin2_boundaries.project_id IS '(DC2Type:uuid)'
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE project_admin1_boundaries (project_id UUID NOT NULL, admin1_boundaries_id INT NOT NULL, PRIMARY KEY(project_id, admin1_boundaries_id))
        SQL);
        $this->addSql(<<<'SQL'
            CREATE INDEX idx_e11b343750c93f70 ON project_admin1_boundaries (admin1_boundaries_id)
        SQL);
        $this->addSql(<<<'SQL'
            CREATE INDEX idx_e11b3437166d1f9c ON project_admin1_boundaries (project_id)
        SQL);
        $this->addSql(<<<'SQL'
            COMMENT ON COLUMN project_admin1_boundaries.project_id IS '(DC2Type:uuid)'
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE admin2_boundaries (id INT NOT NULL, adm2_name VARCHAR(255) NOT NULL, adm2_pcode VARCHAR(255) NOT NULL, adm1_name VARCHAR(255) NOT NULL, adm1_pcode VARCHAR(255) NOT NULL, geometry geometry(GEOMETRY, 0) NOT NULL, PRIMARY KEY(id))
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE admin3_boundaries (id INT NOT NULL, adm3_name VARCHAR(255) NOT NULL, adm3_pcode VARCHAR(255) NOT NULL, adm2_name VARCHAR(255) NOT NULL, adm2_pcode VARCHAR(255) NOT NULL, adm1_name VARCHAR(255) NOT NULL, adm1_pcode VARCHAR(255) NOT NULL, geometry geometry(GEOMETRY, 0) NOT NULL, PRIMARY KEY(id))
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE actor_admin3_boundaries (actor_id UUID NOT NULL, admin3_boundaries_id INT NOT NULL, PRIMARY KEY(actor_id, admin3_boundaries_id))
        SQL);
        $this->addSql(<<<'SQL'
            CREATE INDEX idx_62cd11c3f84f8ee1 ON actor_admin3_boundaries (admin3_boundaries_id)
        SQL);
        $this->addSql(<<<'SQL'
            CREATE INDEX idx_62cd11c310daf24a ON actor_admin3_boundaries (actor_id)
        SQL);
        $this->addSql(<<<'SQL'
            COMMENT ON COLUMN actor_admin3_boundaries.actor_id IS '(DC2Type:uuid)'
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE actor_admin2_boundaries (actor_id UUID NOT NULL, admin2_boundaries_id INT NOT NULL, PRIMARY KEY(actor_id, admin2_boundaries_id))
        SQL);
        $this->addSql(<<<'SQL'
            CREATE INDEX idx_f9685dac41b45509 ON actor_admin2_boundaries (admin2_boundaries_id)
        SQL);
        $this->addSql(<<<'SQL'
            CREATE INDEX idx_f9685dac10daf24a ON actor_admin2_boundaries (actor_id)
        SQL);
        $this->addSql(<<<'SQL'
            COMMENT ON COLUMN actor_admin2_boundaries.actor_id IS '(DC2Type:uuid)'
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE actor_admin1_boundaries (actor_id UUID NOT NULL, admin1_boundaries_id INT NOT NULL, PRIMARY KEY(actor_id, admin1_boundaries_id))
        SQL);
        $this->addSql(<<<'SQL'
            CREATE INDEX idx_8ef68f5c50c93f70 ON actor_admin1_boundaries (admin1_boundaries_id)
        SQL);
        $this->addSql(<<<'SQL'
            CREATE INDEX idx_8ef68f5c10daf24a ON actor_admin1_boundaries (actor_id)
        SQL);
        $this->addSql(<<<'SQL'
            COMMENT ON COLUMN actor_admin1_boundaries.actor_id IS '(DC2Type:uuid)'
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE admin1_boundaries (id INT NOT NULL, adm1_name VARCHAR(255) NOT NULL, adm1_pcode VARCHAR(255) NOT NULL, geometry geometry(GEOMETRY, 0) NOT NULL, PRIMARY KEY(id))
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE project_admin3_boundaries (project_id UUID NOT NULL, admin3_boundaries_id INT NOT NULL, PRIMARY KEY(project_id, admin3_boundaries_id))
        SQL);
        $this->addSql(<<<'SQL'
            CREATE INDEX idx_d20aaa8f84f8ee1 ON project_admin3_boundaries (admin3_boundaries_id)
        SQL);
        $this->addSql(<<<'SQL'
            CREATE INDEX idx_d20aaa8166d1f9c ON project_admin3_boundaries (project_id)
        SQL);
        $this->addSql(<<<'SQL'
            COMMENT ON COLUMN project_admin3_boundaries.project_id IS '(DC2Type:uuid)'
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE project_admin2_boundaries ADD CONSTRAINT fk_9685e6c7166d1f9c FOREIGN KEY (project_id) REFERENCES project (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE project_admin2_boundaries ADD CONSTRAINT fk_9685e6c741b45509 FOREIGN KEY (admin2_boundaries_id) REFERENCES admin2_boundaries (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE project_admin1_boundaries ADD CONSTRAINT fk_e11b3437166d1f9c FOREIGN KEY (project_id) REFERENCES project (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE project_admin1_boundaries ADD CONSTRAINT fk_e11b343750c93f70 FOREIGN KEY (admin1_boundaries_id) REFERENCES admin1_boundaries (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE actor_admin3_boundaries ADD CONSTRAINT fk_62cd11c310daf24a FOREIGN KEY (actor_id) REFERENCES actor (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE actor_admin3_boundaries ADD CONSTRAINT fk_62cd11c3f84f8ee1 FOREIGN KEY (admin3_boundaries_id) REFERENCES admin3_boundaries (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE actor_admin2_boundaries ADD CONSTRAINT fk_f9685dac10daf24a FOREIGN KEY (actor_id) REFERENCES actor (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE actor_admin2_boundaries ADD CONSTRAINT fk_f9685dac41b45509 FOREIGN KEY (admin2_boundaries_id) REFERENCES admin2_boundaries (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE actor_admin1_boundaries ADD CONSTRAINT fk_8ef68f5c10daf24a FOREIGN KEY (actor_id) REFERENCES actor (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE actor_admin1_boundaries ADD CONSTRAINT fk_8ef68f5c50c93f70 FOREIGN KEY (admin1_boundaries_id) REFERENCES admin1_boundaries (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE project_admin3_boundaries ADD CONSTRAINT fk_d20aaa8166d1f9c FOREIGN KEY (project_id) REFERENCES project (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE project_admin3_boundaries ADD CONSTRAINT fk_d20aaa8f84f8ee1 FOREIGN KEY (admin3_boundaries_id) REFERENCES admin3_boundaries (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE actor_admin1_boundary DROP CONSTRAINT FK_AAC7D7C910DAF24A
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE actor_admin1_boundary DROP CONSTRAINT FK_AAC7D7C95F1BC794
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE actor_admin2_boundary DROP CONSTRAINT FK_41F06CCA10DAF24A
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE actor_admin2_boundary DROP CONSTRAINT FK_41F06CCAE2D1AB5A
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE actor_admin3_boundary DROP CONSTRAINT FK_AE3207F410DAF24A
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE actor_admin3_boundary DROP CONSTRAINT FK_AE3207F43F4772DF
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE project_admin1_boundary DROP CONSTRAINT FK_E560333166D1F9C
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE project_admin1_boundary DROP CONSTRAINT FK_E5603335F1BC794
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE project_admin2_boundary DROP CONSTRAINT FK_E561B830166D1F9C
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE project_admin2_boundary DROP CONSTRAINT FK_E561B830E2D1AB5A
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE project_admin3_boundary DROP CONSTRAINT FK_AA3D30E166D1F9C
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE project_admin3_boundary DROP CONSTRAINT FK_AA3D30E3F4772DF
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE actor_admin1_boundary
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE actor_admin2_boundary
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE actor_admin3_boundary
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE admin1_boundary
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE admin2_boundary
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE admin3_boundary
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE project_admin1_boundary
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE project_admin2_boundary
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE project_admin3_boundary
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE project ALTER administrative_scopes DROP NOT NULL
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE media_object ADD created_by INT DEFAULT NULL
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE media_object ADD updated_by INT DEFAULT NULL
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE media_object ADD CONSTRAINT fk_14d43132de12ab56 FOREIGN KEY (created_by) REFERENCES "user" (id) ON DELETE SET NULL NOT DEFERRABLE INITIALLY IMMEDIATE
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE media_object ADD CONSTRAINT fk_14d4313216fe72e1 FOREIGN KEY (updated_by) REFERENCES "user" (id) ON DELETE SET NULL NOT DEFERRABLE INITIALLY IMMEDIATE
        SQL);
        $this->addSql(<<<'SQL'
            CREATE INDEX idx_14d4313216fe72e1 ON media_object (updated_by)
        SQL);
        $this->addSql(<<<'SQL'
            CREATE INDEX idx_14d43132de12ab56 ON media_object (created_by)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE actor ADD office_location geometry(GEOMETRY, 0) DEFAULT NULL
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE actor ALTER administrative_scopes DROP NOT NULL
        SQL);
    }
}
