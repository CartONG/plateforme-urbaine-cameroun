<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250307144552 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('ALTER TABLE project ADD other_beneficiary VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE project ADD other_donor VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE project ADD other_contracting_organisation VARCHAR(255) DEFAULT NULL');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('ALTER TABLE project DROP other_beneficiary');
        $this->addSql('ALTER TABLE project DROP other_donor');
        $this->addSql('ALTER TABLE project DROP other_contracting_organisation');
    }
}
