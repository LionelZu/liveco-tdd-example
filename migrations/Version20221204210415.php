<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20221204210415 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SEQUENCE achat_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE TABLE achat (id INT NOT NULL, adherent_id VARCHAR(255) NOT NULL, code_produit VARCHAR(20) NOT NULL, current_price NUMERIC(10, 2) NOT NULL, negociate_price NUMERIC(10, 2) NOT NULL, quantity NUMERIC(10, 0) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_26A9845625F06C53 ON achat (adherent_id)');
        $this->addSql('CREATE TABLE adherent (id VARCHAR(255) NOT NULL, name VARCHAR(255) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('ALTER TABLE achat ADD CONSTRAINT FK_26A9845625F06C53 FOREIGN KEY (adherent_id) REFERENCES adherent (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('DROP SEQUENCE achat_id_seq CASCADE');
        $this->addSql('ALTER TABLE achat DROP CONSTRAINT FK_26A9845625F06C53');
        $this->addSql('DROP TABLE achat');
        $this->addSql('DROP TABLE adherent');
    }
}
