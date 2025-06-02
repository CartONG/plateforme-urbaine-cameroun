<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250407143031 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            CREATE TABLE project_actor (project_id UUID NOT NULL, actor_id UUID NOT NULL, PRIMARY KEY(project_id, actor_id))
        SQL);
        $this->addSql(<<<'SQL'
            CREATE INDEX IDX_57205F67166D1F9C ON project_actor (project_id)
        SQL);
        $this->addSql(<<<'SQL'
            CREATE INDEX IDX_57205F6710DAF24A ON project_actor (actor_id)
        SQL);
        $this->addSql(<<<'SQL'
            COMMENT ON COLUMN project_actor.project_id IS '(DC2Type:uuid)'
        SQL);
        $this->addSql(<<<'SQL'
            COMMENT ON COLUMN project_actor.actor_id IS '(DC2Type:uuid)'
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE project_actor ADD CONSTRAINT FK_57205F67166D1F9C FOREIGN KEY (project_id) REFERENCES project (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE project_actor ADD CONSTRAINT FK_57205F6710DAF24A FOREIGN KEY (actor_id) REFERENCES actor (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE
        SQL);
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            CREATE SCHEMA public
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE project_actor DROP CONSTRAINT FK_57205F67166D1F9C
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE project_actor DROP CONSTRAINT FK_57205F6710DAF24A
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE project_actor
        SQL);
    }
}
