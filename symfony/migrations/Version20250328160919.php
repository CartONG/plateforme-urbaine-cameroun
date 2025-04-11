<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250328160919 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE project_admin1_boundaries (project_id UUID NOT NULL, admin1_boundaries_id INT NOT NULL, PRIMARY KEY(project_id, admin1_boundaries_id))');
        $this->addSql('CREATE INDEX IDX_E11B3437166D1F9C ON project_admin1_boundaries (project_id)');
        $this->addSql('CREATE INDEX IDX_E11B343750C93F70 ON project_admin1_boundaries (admin1_boundaries_id)');
        $this->addSql('COMMENT ON COLUMN project_admin1_boundaries.project_id IS \'(DC2Type:uuid)\'');
        $this->addSql('CREATE TABLE project_admin2_boundaries (project_id UUID NOT NULL, admin2_boundaries_id INT NOT NULL, PRIMARY KEY(project_id, admin2_boundaries_id))');
        $this->addSql('CREATE INDEX IDX_9685E6C7166D1F9C ON project_admin2_boundaries (project_id)');
        $this->addSql('CREATE INDEX IDX_9685E6C741B45509 ON project_admin2_boundaries (admin2_boundaries_id)');
        $this->addSql('COMMENT ON COLUMN project_admin2_boundaries.project_id IS \'(DC2Type:uuid)\'');
        $this->addSql('CREATE TABLE project_admin3_boundaries (project_id UUID NOT NULL, admin3_boundaries_id INT NOT NULL, PRIMARY KEY(project_id, admin3_boundaries_id))');
        $this->addSql('CREATE INDEX IDX_D20AAA8166D1F9C ON project_admin3_boundaries (project_id)');
        $this->addSql('CREATE INDEX IDX_D20AAA8F84F8EE1 ON project_admin3_boundaries (admin3_boundaries_id)');
        $this->addSql('COMMENT ON COLUMN project_admin3_boundaries.project_id IS \'(DC2Type:uuid)\'');
        $this->addSql('ALTER TABLE project_admin1_boundaries ADD CONSTRAINT FK_E11B3437166D1F9C FOREIGN KEY (project_id) REFERENCES project (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE project_admin1_boundaries ADD CONSTRAINT FK_E11B343750C93F70 FOREIGN KEY (admin1_boundaries_id) REFERENCES admin1_boundaries (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE project_admin2_boundaries ADD CONSTRAINT FK_9685E6C7166D1F9C FOREIGN KEY (project_id) REFERENCES project (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE project_admin2_boundaries ADD CONSTRAINT FK_9685E6C741B45509 FOREIGN KEY (admin2_boundaries_id) REFERENCES admin2_boundaries (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE project_admin3_boundaries ADD CONSTRAINT FK_D20AAA8166D1F9C FOREIGN KEY (project_id) REFERENCES project (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE project_admin3_boundaries ADD CONSTRAINT FK_D20AAA8F84F8EE1 FOREIGN KEY (admin3_boundaries_id) REFERENCES admin3_boundaries (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('CREATE SCHEMA geodata');
        $this->addSql('ALTER TABLE project_admin1_boundaries DROP CONSTRAINT FK_E11B3437166D1F9C');
        $this->addSql('ALTER TABLE project_admin1_boundaries DROP CONSTRAINT FK_E11B343750C93F70');
        $this->addSql('ALTER TABLE project_admin2_boundaries DROP CONSTRAINT FK_9685E6C7166D1F9C');
        $this->addSql('ALTER TABLE project_admin2_boundaries DROP CONSTRAINT FK_9685E6C741B45509');
        $this->addSql('ALTER TABLE project_admin3_boundaries DROP CONSTRAINT FK_D20AAA8166D1F9C');
        $this->addSql('ALTER TABLE project_admin3_boundaries DROP CONSTRAINT FK_D20AAA8F84F8EE1');
        $this->addSql('DROP TABLE project_admin1_boundaries');
        $this->addSql('DROP TABLE project_admin2_boundaries');
        $this->addSql('DROP TABLE project_admin3_boundaries');
    }
}
