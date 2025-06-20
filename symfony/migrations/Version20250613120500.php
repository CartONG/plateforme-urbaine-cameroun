<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250613120500 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            ALTER TABLE actor ADD banoc_url VARCHAR(128) DEFAULT NULL
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE project ADD banoc_url VARCHAR(128) DEFAULT NULL
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE resource ADD banoc_url VARCHAR(128) DEFAULT NULL
        SQL);
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            CREATE SCHEMA public
        SQL);
        $this->addSql(<<<'SQL'
            DROP INDEX UNIQ_BC91F416EC3D194B
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE resource DROP banoc_url
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE resource ALTER thematics DROP NOT NULL
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE resource ALTER odds DROP NOT NULL
        SQL);
        $this->addSql(<<<'SQL'
            DROP INDEX UNIQ_447556F9EC3D194B
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE actor DROP banoc_url
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE actor ALTER administrative_scopes DROP NOT NULL
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE actor ALTER thematics DROP NOT NULL
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE actor ALTER odds DROP NOT NULL
        SQL);
        $this->addSql(<<<'SQL'
            DROP INDEX UNIQ_2FB3D0EEEC3D194B
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE project DROP banoc_url
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE project ALTER thematics DROP NOT NULL
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE project ALTER odds DROP NOT NULL
        SQL);
    }
}
