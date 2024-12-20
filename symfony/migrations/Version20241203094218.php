<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20241203094218 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE resource_thematic (resource_id UUID NOT NULL, thematic_id INT NOT NULL, PRIMARY KEY(resource_id, thematic_id))');
        $this->addSql('CREATE INDEX IDX_D29015DF89329D25 ON resource_thematic (resource_id)');
        $this->addSql('CREATE INDEX IDX_D29015DF2395FCED ON resource_thematic (thematic_id)');
        $this->addSql('COMMENT ON COLUMN resource_thematic.resource_id IS \'(DC2Type:uuid)\'');
        $this->addSql('ALTER TABLE resource_thematic ADD CONSTRAINT FK_D29015DF89329D25 FOREIGN KEY (resource_id) REFERENCES resource (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE resource_thematic ADD CONSTRAINT FK_D29015DF2395FCED FOREIGN KEY (thematic_id) REFERENCES thematic (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE resource ADD geo_data_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE resource ADD format VARCHAR(255) NOT NULL');
        $this->addSql('ALTER TABLE resource ADD start_at TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL');
        $this->addSql('ALTER TABLE resource ADD end_at TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL');
        $this->addSql('COMMENT ON COLUMN resource.start_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('COMMENT ON COLUMN resource.end_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('ALTER TABLE resource ADD CONSTRAINT FK_BC91F41680E32C3E FOREIGN KEY (geo_data_id) REFERENCES geo_data (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_BC91F41680E32C3E ON resource (geo_data_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE resource_thematic DROP CONSTRAINT FK_D29015DF89329D25');
        $this->addSql('ALTER TABLE resource_thematic DROP CONSTRAINT FK_D29015DF2395FCED');
        $this->addSql('DROP TABLE resource_thematic');
        $this->addSql('ALTER TABLE resource DROP CONSTRAINT FK_BC91F41680E32C3E');
        $this->addSql('DROP INDEX UNIQ_BC91F41680E32C3E');
        $this->addSql('ALTER TABLE resource DROP geo_data_id');
        $this->addSql('ALTER TABLE resource DROP format');
        $this->addSql('ALTER TABLE resource DROP start_at');
        $this->addSql('ALTER TABLE resource DROP end_at');
    }
}
