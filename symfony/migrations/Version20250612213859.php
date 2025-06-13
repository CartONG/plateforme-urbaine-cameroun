<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250612213859 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            DROP SEQUENCE actor_expertise_id_seq CASCADE
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE actor_expertise
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE actor DROP other_expertise
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE actor ALTER COLUMN odds TYPE JSON USING odds::json
        SQL);
        $this->addSql(<<<'SQL'
            COMMENT ON COLUMN actor.odds IS NULL
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE project ALTER COLUMN odds TYPE JSON USING odds::json
        SQL);
        $this->addSql(<<<'SQL'
            COMMENT ON COLUMN project.odds IS NULL
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE resource ALTER COLUMN odds TYPE JSON USING odds::json
        SQL);
        $this->addSql(<<<'SQL'
            COMMENT ON COLUMN resource.odds IS NULL
        SQL);
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            CREATE SCHEMA public
        SQL);
        $this->addSql(<<<'SQL'
            CREATE SEQUENCE actor_expertise_id_seq INCREMENT BY 1 MINVALUE 1 START 1
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE actor_expertise (id INT NOT NULL, name VARCHAR(255) NOT NULL, PRIMARY KEY(id))
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE resource ALTER thematics DROP NOT NULL
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE resource ALTER odds TYPE TEXT
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE resource ALTER odds DROP NOT NULL
        SQL);
        $this->addSql(<<<'SQL'
            COMMENT ON COLUMN resource.odds IS '(DC2Type:simple_array)'
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE actor ADD other_expertise VARCHAR(255) DEFAULT NULL
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE actor ALTER administrative_scopes DROP NOT NULL
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE actor ALTER thematics DROP NOT NULL
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE actor ALTER odds TYPE TEXT
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE actor ALTER odds DROP NOT NULL
        SQL);
        $this->addSql(<<<'SQL'
            COMMENT ON COLUMN actor.odds IS '(DC2Type:simple_array)'
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE project ALTER thematics DROP NOT NULL
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE project ALTER odds TYPE TEXT
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE project ALTER odds DROP NOT NULL
        SQL);
        $this->addSql(<<<'SQL'
            COMMENT ON COLUMN project.odds IS '(DC2Type:simple_array)'
        SQL);
    }
}
