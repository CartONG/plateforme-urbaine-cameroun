<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250407142831 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            ALTER TABLE project DROP CONSTRAINT fk_2fb3d0eec38a4c7d
        SQL);
        $this->addSql(<<<'SQL'
            DROP INDEX idx_2fb3d0eec38a4c7d
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE project DROP contracting_organisation_id
        SQL);
        $this->addSql("UPDATE project SET administrative_scopes = 'national' WHERE administrative_scopes IS NULL");
        $this->addSql(<<<'SQL'
            ALTER TABLE project ALTER administrative_scopes SET NOT NULL
        SQL);
        $this->addSql("UPDATE project SET financing_types = 'autre' WHERE financing_types IS NULL");
        $this->addSql(<<<'SQL'
            ALTER TABLE project ALTER financing_types SET DEFAULT 'autre'
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE project ALTER financing_types SET NOT NULL
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE project RENAME COLUMN other_contracting_organisation TO other_actor_in_charge
        SQL);
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            CREATE SCHEMA public
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE project ADD contracting_organisation_id INT NOT NULL
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE project ALTER administrative_scopes DROP NOT NULL
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE project ALTER financing_types DROP NOT NULL
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE project RENAME COLUMN other_actor_in_charge TO other_contracting_organisation
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE project ADD CONSTRAINT fk_2fb3d0eec38a4c7d FOREIGN KEY (contracting_organisation_id) REFERENCES organisation (id) NOT DEFERRABLE INITIALLY IMMEDIATE
        SQL);
        $this->addSql(<<<'SQL'
            CREATE INDEX idx_2fb3d0eec38a4c7d ON project (contracting_organisation_id)
        SQL);
    }
}
