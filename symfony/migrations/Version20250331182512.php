<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250331182512 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE resource ADD other_type VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE resource ADD other_thematic VARCHAR(255) DEFAULT NULL');

        $this->addSql("INSERT INTO actor_expertise (id, name) VALUES (nextval('actor_expertise_id_seq'), 'Autre')");

        $this->addSql("INSERT INTO thematic (id, name) VALUES (nextval('thematic_id_seq'), 'Autre')");
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE resource DROP other_type');
        $this->addSql('ALTER TABLE resource DROP other_thematic');
    }
}
