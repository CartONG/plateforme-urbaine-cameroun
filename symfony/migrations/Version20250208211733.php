<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250208211733 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE project ADD logo_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE project DROP logo');
        $this->addSql('ALTER TABLE project ADD CONSTRAINT FK_2FB3D0EEF98F144A FOREIGN KEY (logo_id) REFERENCES file (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE INDEX IDX_2FB3D0EEF98F144A ON project (logo_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE project DROP CONSTRAINT FK_2FB3D0EEF98F144A');
        $this->addSql('DROP INDEX IDX_2FB3D0EEF98F144A');
        $this->addSql('ALTER TABLE project ADD logo VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE project DROP logo_id');
    }
}
