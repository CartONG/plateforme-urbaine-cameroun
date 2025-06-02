<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250307120634 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('ALTER TABLE actor ADD other_expertise VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE actor ADD other_thematic VARCHAR(255) DEFAULT NULL');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('ALTER TABLE actor DROP other_expertise');
        $this->addSql('ALTER TABLE actor DROP other_thematic');
    }
}
