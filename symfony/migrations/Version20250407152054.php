<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250407152054 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            DROP SEQUENCE organisation_id_seq CASCADE
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE organisation DROP CONSTRAINT fk_e6e132b410daf24a
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE organisation
        SQL);
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            CREATE SCHEMA public
        SQL);
        $this->addSql(<<<'SQL'
            CREATE SEQUENCE organisation_id_seq INCREMENT BY 1 MINVALUE 1 START 1
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE organisation (id INT NOT NULL, actor_id UUID DEFAULT NULL, name VARCHAR(255) NOT NULL, acronym VARCHAR(255) NOT NULL, contracting BOOLEAN DEFAULT false NOT NULL, donor BOOLEAN DEFAULT false NOT NULL, PRIMARY KEY(id))
        SQL);
        $this->addSql(<<<'SQL'
            CREATE UNIQUE INDEX uniq_e6e132b410daf24a ON organisation (actor_id)
        SQL);
        $this->addSql(<<<'SQL'
            COMMENT ON COLUMN organisation.actor_id IS '(DC2Type:uuid)'
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE organisation ADD CONSTRAINT fk_e6e132b410daf24a FOREIGN KEY (actor_id) REFERENCES actor (id) NOT DEFERRABLE INITIALLY IMMEDIATE
        SQL);
    }
}
