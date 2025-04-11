<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250327175828 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP SEQUENCE administrative_scope_id_seq CASCADE');
        $this->addSql('CREATE TABLE actor_admin1_boundaries (actor_id UUID NOT NULL, admin1_boundaries_id INT NOT NULL, PRIMARY KEY(actor_id, admin1_boundaries_id))');
        $this->addSql('CREATE INDEX IDX_8EF68F5C10DAF24A ON actor_admin1_boundaries (actor_id)');
        $this->addSql('CREATE INDEX IDX_8EF68F5C50C93F70 ON actor_admin1_boundaries (admin1_boundaries_id)');
        $this->addSql('COMMENT ON COLUMN actor_admin1_boundaries.actor_id IS \'(DC2Type:uuid)\'');
        $this->addSql('CREATE TABLE actor_admin2_boundaries (actor_id UUID NOT NULL, admin2_boundaries_id INT NOT NULL, PRIMARY KEY(actor_id, admin2_boundaries_id))');
        $this->addSql('CREATE INDEX IDX_F9685DAC10DAF24A ON actor_admin2_boundaries (actor_id)');
        $this->addSql('CREATE INDEX IDX_F9685DAC41B45509 ON actor_admin2_boundaries (admin2_boundaries_id)');
        $this->addSql('COMMENT ON COLUMN actor_admin2_boundaries.actor_id IS \'(DC2Type:uuid)\'');
        $this->addSql('CREATE TABLE actor_admin3_boundaries (actor_id UUID NOT NULL, admin3_boundaries_id INT NOT NULL, PRIMARY KEY(actor_id, admin3_boundaries_id))');
        $this->addSql('CREATE INDEX IDX_62CD11C310DAF24A ON actor_admin3_boundaries (actor_id)');
        $this->addSql('CREATE INDEX IDX_62CD11C3F84F8EE1 ON actor_admin3_boundaries (admin3_boundaries_id)');
        $this->addSql('COMMENT ON COLUMN actor_admin3_boundaries.actor_id IS \'(DC2Type:uuid)\'');
        $this->addSql('ALTER TABLE actor_admin1_boundaries ADD CONSTRAINT FK_8EF68F5C10DAF24A FOREIGN KEY (actor_id) REFERENCES actor (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE actor_admin1_boundaries ADD CONSTRAINT FK_8EF68F5C50C93F70 FOREIGN KEY (admin1_boundaries_id) REFERENCES admin1_boundaries (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE actor_admin2_boundaries ADD CONSTRAINT FK_F9685DAC10DAF24A FOREIGN KEY (actor_id) REFERENCES actor (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE actor_admin2_boundaries ADD CONSTRAINT FK_F9685DAC41B45509 FOREIGN KEY (admin2_boundaries_id) REFERENCES admin2_boundaries (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE actor_admin3_boundaries ADD CONSTRAINT FK_62CD11C310DAF24A FOREIGN KEY (actor_id) REFERENCES actor (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE actor_admin3_boundaries ADD CONSTRAINT FK_62CD11C3F84F8EE1 FOREIGN KEY (admin3_boundaries_id) REFERENCES admin3_boundaries (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE actor_administrative_scope DROP CONSTRAINT fk_65ebe3cc10daf24a');
        $this->addSql('ALTER TABLE actor_administrative_scope DROP CONSTRAINT fk_65ebe3ccc1892e43');
        $this->addSql('DROP TABLE administrative_scope');
        $this->addSql('DROP TABLE actor_administrative_scope');
        $this->addSql('ALTER TABLE actor ADD administrative_scopes TEXT');
        $this->addSql('ALTER TABLE actor ALTER COLUMN administrative_scopes DROP NOT NULL');
        $this->addSql('COMMENT ON COLUMN actor.administrative_scopes IS \'(DC2Type:simple_array)\'');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE administrative_scope (id INT NOT NULL, name VARCHAR(255) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE TABLE actor_administrative_scope (actor_id UUID NOT NULL, administrative_scope_id INT NOT NULL, PRIMARY KEY(actor_id, administrative_scope_id))');
        $this->addSql('CREATE INDEX idx_65ebe3ccc1892e43 ON actor_administrative_scope (administrative_scope_id)');
        $this->addSql('CREATE INDEX idx_65ebe3cc10daf24a ON actor_administrative_scope (actor_id)');
        $this->addSql('COMMENT ON COLUMN actor_administrative_scope.actor_id IS \'(DC2Type:uuid)\'');
        $this->addSql('ALTER TABLE actor_administrative_scope ADD CONSTRAINT fk_65ebe3cc10daf24a FOREIGN KEY (actor_id) REFERENCES actor (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE actor_administrative_scope ADD CONSTRAINT fk_65ebe3ccc1892e43 FOREIGN KEY (administrative_scope_id) REFERENCES administrative_scope (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE actor_admin1_boundaries DROP CONSTRAINT FK_8EF68F5C10DAF24A');
        $this->addSql('ALTER TABLE actor_admin1_boundaries DROP CONSTRAINT FK_8EF68F5C50C93F70');
        $this->addSql('ALTER TABLE actor_admin2_boundaries DROP CONSTRAINT FK_F9685DAC10DAF24A');
        $this->addSql('ALTER TABLE actor_admin2_boundaries DROP CONSTRAINT FK_F9685DAC41B45509');
        $this->addSql('ALTER TABLE actor_admin3_boundaries DROP CONSTRAINT FK_62CD11C310DAF24A');
        $this->addSql('ALTER TABLE actor_admin3_boundaries DROP CONSTRAINT FK_62CD11C3F84F8EE1');
        $this->addSql('DROP TABLE actor_admin1_boundaries');
        $this->addSql('DROP TABLE actor_admin2_boundaries');
        $this->addSql('DROP TABLE actor_admin3_boundaries');
    }
}
