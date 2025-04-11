<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250303141957 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SEQUENCE app_content_comment_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE TABLE app_content_comment (id INT NOT NULL, created_by INT DEFAULT NULL, updated_by INT DEFAULT NULL, read_by_admin BOOLEAN NOT NULL, origin VARCHAR(255) NOT NULL, message TEXT NOT NULL, location geometry(POINT, 0) DEFAULT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, updated_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_11A6AE56DE12AB56 ON app_content_comment (created_by)');
        $this->addSql('CREATE INDEX IDX_11A6AE5616FE72E1 ON app_content_comment (updated_by)');
        $this->addSql('ALTER TABLE app_content_comment ADD CONSTRAINT FK_11A6AE56DE12AB56 FOREIGN KEY (created_by) REFERENCES "user" (id) ON DELETE SET NULL NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE app_content_comment ADD CONSTRAINT FK_11A6AE5616FE72E1 FOREIGN KEY (updated_by) REFERENCES "user" (id) ON DELETE SET NULL NOT DEFERRABLE INITIALLY IMMEDIATE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('DROP SEQUENCE app_content_comment_id_seq CASCADE');
        $this->addSql('ALTER TABLE app_content_comment DROP CONSTRAINT FK_11A6AE56DE12AB56');
        $this->addSql('ALTER TABLE app_content_comment DROP CONSTRAINT FK_11A6AE5616FE72E1');
        $this->addSql('DROP TABLE app_content_comment');
    }
}
