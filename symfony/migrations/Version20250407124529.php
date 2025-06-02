<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250407124529 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            ALTER TABLE projects_donors DROP CONSTRAINT fk_b446a753166d1f9c
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE projects_donors DROP CONSTRAINT fk_b446a7539e6b1585
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE projects_donors
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE project ADD financing_types VARCHAR(255) DEFAULT NULL
        SQL);
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            CREATE SCHEMA public
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE projects_donors (project_id UUID NOT NULL, organisation_id INT NOT NULL, PRIMARY KEY(project_id, organisation_id))
        SQL);
        $this->addSql(<<<'SQL'
            CREATE INDEX idx_b446a7539e6b1585 ON projects_donors (organisation_id)
        SQL);
        $this->addSql(<<<'SQL'
            CREATE INDEX idx_b446a753166d1f9c ON projects_donors (project_id)
        SQL);
        $this->addSql(<<<'SQL'
            COMMENT ON COLUMN projects_donors.project_id IS '(DC2Type:uuid)'
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE projects_donors ADD CONSTRAINT fk_b446a753166d1f9c FOREIGN KEY (project_id) REFERENCES project (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE projects_donors ADD CONSTRAINT fk_b446a7539e6b1585 FOREIGN KEY (organisation_id) REFERENCES organisation (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE project DROP financing_types
        SQL);
    }
}
