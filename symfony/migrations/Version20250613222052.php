<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250613222052 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Fix refresh_tokens auto-increment';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('ALTER TABLE refresh_tokens ALTER COLUMN id SET DEFAULT nextval(\'refresh_tokens_id_seq\')');
        $this->addSql('ALTER SEQUENCE refresh_tokens_id_seq OWNED BY refresh_tokens.id');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('ALTER TABLE refresh_tokens ALTER COLUMN id DROP DEFAULT');
        $this->addSql('ALTER SEQUENCE refresh_tokens_id_seq OWNED BY NONE');
    }
}
