<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250614134915 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        $this->addSql(<<<'SQL'
            ALTER TABLE resource_admin2_boundary DROP CONSTRAINT fk_92fd711989329d25
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE resource_admin2_boundary DROP CONSTRAINT fk_92fd7119e2d1ab5a
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE project_admin2_boundary DROP CONSTRAINT fk_e561b830166d1f9c
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE project_admin2_boundary DROP CONSTRAINT fk_e561b830e2d1ab5a
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE actor_admin2_boundary DROP CONSTRAINT fk_41f06cca10daf24a
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE actor_admin2_boundary DROP CONSTRAINT fk_41f06ccae2d1ab5a
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE admin2_boundary
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE resource_admin2_boundary
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE project_admin2_boundary
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE actor_admin2_boundary
        SQL);
    }

    public function down(Schema $schema): void
    {
        $this->addSql(<<<'SQL'
            CREATE SEQUENCE admin2_boundary_id_seq INCREMENT BY 1 MINVALUE 1 START 1
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE admin2_boundary (id INT NOT NULL, adm2_name VARCHAR(255) NOT NULL, adm2_pcode VARCHAR(255) NOT NULL, adm1_name VARCHAR(255) NOT NULL, adm1_pcode VARCHAR(255) NOT NULL, geometry geometry(GEOMETRY, 0) NOT NULL, PRIMARY KEY(id))
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE resource_admin2_boundary (resource_id UUID NOT NULL, admin2_boundary_id INT NOT NULL, PRIMARY KEY(resource_id, admin2_boundary_id))
        SQL);
        $this->addSql(<<<'SQL'
            CREATE INDEX idx_92fd7119e2d1ab5a ON resource_admin2_boundary (admin2_boundary_id)
        SQL);
        $this->addSql(<<<'SQL'
            CREATE INDEX idx_92fd711989329d25 ON resource_admin2_boundary (resource_id)
        SQL);
        $this->addSql(<<<'SQL'
            COMMENT ON COLUMN resource_admin2_boundary.resource_id IS '(DC2Type:uuid)'
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE project_admin2_boundary (project_id UUID NOT NULL, admin2_boundary_id INT NOT NULL, PRIMARY KEY(project_id, admin2_boundary_id))
        SQL);
        $this->addSql(<<<'SQL'
            CREATE INDEX idx_e561b830e2d1ab5a ON project_admin2_boundary (admin2_boundary_id)
        SQL);
        $this->addSql(<<<'SQL'
            CREATE INDEX idx_e561b830166d1f9c ON project_admin2_boundary (project_id)
        SQL);
        $this->addSql(<<<'SQL'
            COMMENT ON COLUMN project_admin2_boundary.project_id IS '(DC2Type:uuid)'
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE actor_admin2_boundary (actor_id UUID NOT NULL, admin2_boundary_id INT NOT NULL, PRIMARY KEY(actor_id, admin2_boundary_id))
        SQL);
        $this->addSql(<<<'SQL'
            CREATE INDEX idx_41f06ccae2d1ab5a ON actor_admin2_boundary (admin2_boundary_id)
        SQL);
        $this->addSql(<<<'SQL'
            CREATE INDEX idx_41f06cca10daf24a ON actor_admin2_boundary (actor_id)
        SQL);
        $this->addSql(<<<'SQL'
            COMMENT ON COLUMN actor_admin2_boundary.actor_id IS '(DC2Type:uuid)'
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE resource_admin2_boundary ADD CONSTRAINT fk_92fd711989329d25 FOREIGN KEY (resource_id) REFERENCES resource (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE resource_admin2_boundary ADD CONSTRAINT fk_92fd7119e2d1ab5a FOREIGN KEY (admin2_boundary_id) REFERENCES admin2_boundary (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE project_admin2_boundary ADD CONSTRAINT fk_e561b830166d1f9c FOREIGN KEY (project_id) REFERENCES project (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE project_admin2_boundary ADD CONSTRAINT fk_e561b830e2d1ab5a FOREIGN KEY (admin2_boundary_id) REFERENCES admin2_boundary (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE actor_admin2_boundary ADD CONSTRAINT fk_41f06cca10daf24a FOREIGN KEY (actor_id) REFERENCES actor (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE actor_admin2_boundary ADD CONSTRAINT fk_41f06ccae2d1ab5a FOREIGN KEY (admin2_boundary_id) REFERENCES admin2_boundary (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE
        SQL);
    }
}
