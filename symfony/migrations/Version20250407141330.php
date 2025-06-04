<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250407141330 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        $this->addSql(<<<'SQL'
            ALTER TABLE project RENAME COLUMN other_donor TO other_financing_type
        SQL);
        $this->addSql(<<<'SQL'
            COMMENT ON COLUMN project.financing_types IS '(DC2Type:simple_array)'
        SQL);
    }

    public function down(Schema $schema): void
    {
        $this->addSql(<<<'SQL'
            ALTER TABLE project RENAME COLUMN other_financing_type TO other_donor
        SQL);
        $this->addSql(<<<'SQL'
            COMMENT ON COLUMN project.financing_types IS NULL
        SQL);
    }
}
