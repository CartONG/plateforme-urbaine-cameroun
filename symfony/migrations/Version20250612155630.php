<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250612155630 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            DROP SEQUENCE thematic_id_seq CASCADE
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE actor_actor_expertise DROP CONSTRAINT fk_4a438ead10daf24a
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE actor_actor_expertise DROP CONSTRAINT fk_4a438ead916905ac
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE actor_thematic DROP CONSTRAINT fk_d159a26410daf24a
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE actor_thematic DROP CONSTRAINT fk_d159a2642395fced
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE resource_thematic DROP CONSTRAINT fk_d29015df89329d25
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE resource_thematic DROP CONSTRAINT fk_d29015df2395fced
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE project_thematic DROP CONSTRAINT fk_415254a9166d1f9c
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE project_thematic DROP CONSTRAINT fk_415254a92395fced
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE actor_actor_expertise
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE actor_thematic
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE thematic
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE resource_thematic
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE project_thematic
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE actor ADD thematics TEXT DEFAULT NULL
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE actor ADD odds TEXT DEFAULT NULL
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE actor ADD banoc VARCHAR(128) DEFAULT NULL
        SQL);
        $this->addSql(<<<'SQL'
            COMMENT ON COLUMN actor.thematics IS '(DC2Type:simple_array)'
        SQL);
        $this->addSql(<<<'SQL'
            COMMENT ON COLUMN actor.odds IS '(DC2Type:simple_array)'
        SQL);
        $this->addSql(<<<'SQL'
            CREATE UNIQUE INDEX UNIQ_447556F9D2DDE2F6 ON actor (banoc)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE project ADD thematics TEXT DEFAULT NULL
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE project ADD odds TEXT DEFAULT NULL
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE project ADD banoc VARCHAR(128) DEFAULT NULL
        SQL);
        $this->addSql(<<<'SQL'
            COMMENT ON COLUMN project.thematics IS '(DC2Type:simple_array)'
        SQL);
        $this->addSql(<<<'SQL'
            COMMENT ON COLUMN project.odds IS '(DC2Type:simple_array)'
        SQL);
        $this->addSql(<<<'SQL'
            CREATE UNIQUE INDEX UNIQ_2FB3D0EED2DDE2F6 ON project (banoc)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE resource ADD thematics TEXT DEFAULT NULL
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE resource ADD odds TEXT DEFAULT NULL
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE resource ADD banoc VARCHAR(128) DEFAULT NULL
        SQL);
        $this->addSql(<<<'SQL'
            COMMENT ON COLUMN resource.thematics IS '(DC2Type:simple_array)'
        SQL);
        $this->addSql(<<<'SQL'
            COMMENT ON COLUMN resource.odds IS '(DC2Type:simple_array)'
        SQL);
        $this->addSql(<<<'SQL'
            CREATE UNIQUE INDEX UNIQ_BC91F416D2DDE2F6 ON resource (banoc)
        SQL);
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            CREATE SCHEMA public
        SQL);
        $this->addSql(<<<'SQL'
            CREATE SEQUENCE thematic_id_seq INCREMENT BY 1 MINVALUE 1 START 1
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE actor_actor_expertise (actor_id UUID NOT NULL, actor_expertise_id INT NOT NULL, PRIMARY KEY(actor_id, actor_expertise_id))
        SQL);
        $this->addSql(<<<'SQL'
            CREATE INDEX idx_4a438ead916905ac ON actor_actor_expertise (actor_expertise_id)
        SQL);
        $this->addSql(<<<'SQL'
            CREATE INDEX idx_4a438ead10daf24a ON actor_actor_expertise (actor_id)
        SQL);
        $this->addSql(<<<'SQL'
            COMMENT ON COLUMN actor_actor_expertise.actor_id IS '(DC2Type:uuid)'
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE actor_thematic (actor_id UUID NOT NULL, thematic_id INT NOT NULL, PRIMARY KEY(actor_id, thematic_id))
        SQL);
        $this->addSql(<<<'SQL'
            CREATE INDEX idx_d159a2642395fced ON actor_thematic (thematic_id)
        SQL);
        $this->addSql(<<<'SQL'
            CREATE INDEX idx_d159a26410daf24a ON actor_thematic (actor_id)
        SQL);
        $this->addSql(<<<'SQL'
            COMMENT ON COLUMN actor_thematic.actor_id IS '(DC2Type:uuid)'
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE thematic (id INT NOT NULL, name VARCHAR(255) NOT NULL, PRIMARY KEY(id))
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE resource_thematic (resource_id UUID NOT NULL, thematic_id INT NOT NULL, PRIMARY KEY(resource_id, thematic_id))
        SQL);
        $this->addSql(<<<'SQL'
            CREATE INDEX idx_d29015df2395fced ON resource_thematic (thematic_id)
        SQL);
        $this->addSql(<<<'SQL'
            CREATE INDEX idx_d29015df89329d25 ON resource_thematic (resource_id)
        SQL);
        $this->addSql(<<<'SQL'
            COMMENT ON COLUMN resource_thematic.resource_id IS '(DC2Type:uuid)'
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE project_thematic (project_id UUID NOT NULL, thematic_id INT NOT NULL, PRIMARY KEY(project_id, thematic_id))
        SQL);
        $this->addSql(<<<'SQL'
            CREATE INDEX idx_415254a92395fced ON project_thematic (thematic_id)
        SQL);
        $this->addSql(<<<'SQL'
            CREATE INDEX idx_415254a9166d1f9c ON project_thematic (project_id)
        SQL);
        $this->addSql(<<<'SQL'
            COMMENT ON COLUMN project_thematic.project_id IS '(DC2Type:uuid)'
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE actor_actor_expertise ADD CONSTRAINT fk_4a438ead10daf24a FOREIGN KEY (actor_id) REFERENCES actor (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE actor_actor_expertise ADD CONSTRAINT fk_4a438ead916905ac FOREIGN KEY (actor_expertise_id) REFERENCES actor_expertise (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE actor_thematic ADD CONSTRAINT fk_d159a26410daf24a FOREIGN KEY (actor_id) REFERENCES actor (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE actor_thematic ADD CONSTRAINT fk_d159a2642395fced FOREIGN KEY (thematic_id) REFERENCES thematic (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE resource_thematic ADD CONSTRAINT fk_d29015df89329d25 FOREIGN KEY (resource_id) REFERENCES resource (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE resource_thematic ADD CONSTRAINT fk_d29015df2395fced FOREIGN KEY (thematic_id) REFERENCES thematic (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE project_thematic ADD CONSTRAINT fk_415254a9166d1f9c FOREIGN KEY (project_id) REFERENCES project (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE project_thematic ADD CONSTRAINT fk_415254a92395fced FOREIGN KEY (thematic_id) REFERENCES thematic (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE
        SQL);
        $this->addSql(<<<'SQL'
            DROP INDEX UNIQ_447556F9D2DDE2F6
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE actor DROP thematics
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE actor DROP odds
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE actor DROP banoc
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE actor ALTER administrative_scopes DROP NOT NULL
        SQL);
        $this->addSql(<<<'SQL'
            DROP INDEX UNIQ_2FB3D0EED2DDE2F6
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE project DROP thematics
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE project DROP odds
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE project DROP banoc
        SQL);
        $this->addSql(<<<'SQL'
            DROP INDEX UNIQ_BC91F416D2DDE2F6
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE resource DROP thematics
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE resource DROP odds
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE resource DROP banoc
        SQL);
    }
}
