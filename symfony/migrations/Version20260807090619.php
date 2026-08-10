<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20260807090619 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            DROP SEQUENCE admin2_boundary_id_seq CASCADE
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE actor ALTER administrative_scopes SET NOT NULL
        SQL);
        $this->addSql(<<<'SQL'
            CREATE UNIQUE INDEX UNIQ_447556F9EC3D194B ON actor (banoc_url)
        SQL);
        $this->addSql(<<<'SQL'
            CREATE UNIQUE INDEX UNIQ_2FB3D0EEEC3D194B ON project (banoc_url)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE refresh_tokens ALTER id DROP DEFAULT
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE resource ALTER administrative_scopes SET NOT NULL
        SQL);
        $this->addSql(<<<'SQL'
            CREATE UNIQUE INDEX UNIQ_BC91F416EC3D194B ON resource (banoc_url)
        SQL);
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            CREATE SCHEMA public
        SQL);
        $this->addSql(<<<'SQL'
            CREATE SEQUENCE admin2_boundary_id_seq INCREMENT BY 1 MINVALUE 1 START 1
        SQL);
        $this->addSql(<<<'SQL'
            CREATE SEQUENCE refresh_tokens_id_seq
        SQL);
        $this->addSql(<<<'SQL'
            SELECT setval('refresh_tokens_id_seq', (SELECT MAX(id) FROM refresh_tokens))
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE refresh_tokens ALTER id SET DEFAULT nextval('refresh_tokens_id_seq')
        SQL);
        $this->addSql(<<<'SQL'
            DROP INDEX UNIQ_2FB3D0EEEC3D194B
        SQL);
        $this->addSql(<<<'SQL'
            DROP INDEX UNIQ_447556F9EC3D194B
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE actor ALTER administrative_scopes DROP NOT NULL
        SQL);
        $this->addSql(<<<'SQL'
            DROP INDEX UNIQ_BC91F416EC3D194B
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE resource ALTER administrative_scopes DROP NOT NULL
        SQL);
    }
}
