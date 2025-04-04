<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20241126155911 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SEQUENCE user_password_token_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE TABLE user_password_token (id INT NOT NULL, user_id INT NOT NULL, token VARCHAR(50) NOT NULL, expires_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_D52EB37D5F37A13B ON user_password_token (token)');
        $this->addSql('CREATE INDEX IDX_D52EB37DA76ED395 ON user_password_token (user_id)');
        $this->addSql('ALTER TABLE user_password_token ADD CONSTRAINT FK_D52EB37DA76ED395 FOREIGN KEY (user_id) REFERENCES "user" (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('DROP SEQUENCE user_password_token_id_seq CASCADE');
        $this->addSql('ALTER TABLE user_password_token DROP CONSTRAINT FK_D52EB37DA76ED395');
        $this->addSql('DROP TABLE user_password_token');
    }
}
