<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250403172303 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            CREATE TABLE resource_admin1_boundary (resource_id UUID NOT NULL, admin1_boundary_id INT NOT NULL, PRIMARY KEY(resource_id, admin1_boundary_id))
        SQL);
        $this->addSql(<<<'SQL'
            CREATE INDEX IDX_79CACA1A89329D25 ON resource_admin1_boundary (resource_id)
        SQL);
        $this->addSql(<<<'SQL'
            CREATE INDEX IDX_79CACA1A5F1BC794 ON resource_admin1_boundary (admin1_boundary_id)
        SQL);
        $this->addSql(<<<'SQL'
            COMMENT ON COLUMN resource_admin1_boundary.resource_id IS '(DC2Type:uuid)'
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE resource_admin2_boundary (resource_id UUID NOT NULL, admin2_boundary_id INT NOT NULL, PRIMARY KEY(resource_id, admin2_boundary_id))
        SQL);
        $this->addSql(<<<'SQL'
            CREATE INDEX IDX_92FD711989329D25 ON resource_admin2_boundary (resource_id)
        SQL);
        $this->addSql(<<<'SQL'
            CREATE INDEX IDX_92FD7119E2D1AB5A ON resource_admin2_boundary (admin2_boundary_id)
        SQL);
        $this->addSql(<<<'SQL'
            COMMENT ON COLUMN resource_admin2_boundary.resource_id IS '(DC2Type:uuid)'
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE resource_admin3_boundary (resource_id UUID NOT NULL, admin3_boundary_id INT NOT NULL, PRIMARY KEY(resource_id, admin3_boundary_id))
        SQL);
        $this->addSql(<<<'SQL'
            CREATE INDEX IDX_7D3F1A2789329D25 ON resource_admin3_boundary (resource_id)
        SQL);
        $this->addSql(<<<'SQL'
            CREATE INDEX IDX_7D3F1A273F4772DF ON resource_admin3_boundary (admin3_boundary_id)
        SQL);
        $this->addSql(<<<'SQL'
            COMMENT ON COLUMN resource_admin3_boundary.resource_id IS '(DC2Type:uuid)'
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE resource_admin1_boundary ADD CONSTRAINT FK_79CACA1A89329D25 FOREIGN KEY (resource_id) REFERENCES resource (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE resource_admin1_boundary ADD CONSTRAINT FK_79CACA1A5F1BC794 FOREIGN KEY (admin1_boundary_id) REFERENCES admin1_boundary (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE resource_admin2_boundary ADD CONSTRAINT FK_92FD711989329D25 FOREIGN KEY (resource_id) REFERENCES resource (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE resource_admin2_boundary ADD CONSTRAINT FK_92FD7119E2D1AB5A FOREIGN KEY (admin2_boundary_id) REFERENCES admin2_boundary (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE resource_admin3_boundary ADD CONSTRAINT FK_7D3F1A2789329D25 FOREIGN KEY (resource_id) REFERENCES resource (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE resource_admin3_boundary ADD CONSTRAINT FK_7D3F1A273F4772DF FOREIGN KEY (admin3_boundary_id) REFERENCES admin3_boundary (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE
        SQL);
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            CREATE SCHEMA public
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE resource_admin1_boundary DROP CONSTRAINT FK_79CACA1A89329D25
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE resource_admin1_boundary DROP CONSTRAINT FK_79CACA1A5F1BC794
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE resource_admin2_boundary DROP CONSTRAINT FK_92FD711989329D25
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE resource_admin2_boundary DROP CONSTRAINT FK_92FD7119E2D1AB5A
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE resource_admin3_boundary DROP CONSTRAINT FK_7D3F1A2789329D25
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE resource_admin3_boundary DROP CONSTRAINT FK_7D3F1A273F4772DF
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE resource_admin1_boundary
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE resource_admin2_boundary
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE resource_admin3_boundary
        SQL);
    }
}
