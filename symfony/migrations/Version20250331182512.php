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

        $this->addSql("DELETE FROM organisation WHERE name LIKE 'Deutsche Gesellschaft für Internationale Zusammenarbeit'");
        $this->addSql("DELETE FROM organisation WHERE name LIKE 'Association Humanitaire de Solidarité Internationale'");
        $this->addSql("DELETE FROM organisation WHERE name LIKE 'ONU-Habitat'");
        $this->addSql("DELETE FROM organisation WHERE name LIKE 'Bundesministerium für wirtschaftliche Zusammenarbeit und Entwicklung'");
        $this->addSql("DELETE FROM organisation WHERE name LIKE 'Crédit Foncier du Cameroun'");
        $this->addSql("DELETE FROM organisation WHERE name LIKE 'Union Européenne'");

        $this->addSql("INSERT INTO actor_expertise (id, name) VALUES (nextval('actor_expertise_id_seq'), 'Autre')");

        $this->addSql("INSERT INTO organisation (id, name, acronym, contracting, donor) VALUES (nextval('organisation_id_seq'), 'Autre', 'Others', true, true)");
        $this->addSql("INSERT INTO organisation (id, name, acronym, contracting, donor) VALUES (nextval('organisation_id_seq'), 'Fonds propres', 'FP', true, true)");
        $this->addSql("INSERT INTO organisation (id, name, acronym, contracting, donor) VALUES (nextval('organisation_id_seq'), 'Subvention publique', 'SP', true, true)");
        $this->addSql("INSERT INTO organisation (id, name, acronym, contracting, donor) VALUES (nextval('organisation_id_seq'), 'Prêt bancaire', 'PB', true, true)");
        $this->addSql("INSERT INTO organisation (id, name, acronym, contracting, donor) VALUES (nextval('organisation_id_seq'), 'Investissement privé', 'IP', true, true)");
        $this->addSql("INSERT INTO organisation (id, name, acronym, contracting, donor) VALUES (nextval('organisation_id_seq'), 'Crowdfunding (financement participatif)', 'CF', true, true)");
        $this->addSql("INSERT INTO organisation (id, name, acronym, contracting, donor) VALUES (nextval('organisation_id_seq'), 'Partenariat public-privé', 'PPP', true, true)");
        $this->addSql("INSERT INTO organisation (id, name, acronym, contracting, donor) VALUES (nextval('organisation_id_seq'), 'Mécénat', 'MC', true, true)");

        $this->addSql("INSERT INTO thematic (id, name) VALUES (nextval('thematic_id_seq'), 'Autre')");
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE resource DROP other_type');
        $this->addSql('ALTER TABLE resource DROP other_thematic');
    }
}
