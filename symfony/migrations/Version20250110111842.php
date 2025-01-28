<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250110111842 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SEQUENCE highlighted_item_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE TABLE highlighted_item (id INT NOT NULL, item_id VARCHAR(255) NOT NULL, is_highlighted BOOLEAN NOT NULL, highlighted_at TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL, position INT DEFAULT NULL, item_type VARCHAR(255) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_1BE98136126F525E ON highlighted_item (item_id)');
        $this->addSql('COMMENT ON COLUMN highlighted_item.highlighted_at IS \'(DC2Type:datetime_immutable)\'');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('DROP SEQUENCE highlighted_item_id_seq CASCADE');
        $this->addSql('DROP TABLE highlighted_item');
    }
}
