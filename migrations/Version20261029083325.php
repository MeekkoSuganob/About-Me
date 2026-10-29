<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20261029083325 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE item (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(100) NOT NULL, code VARCHAR(50) NOT NULL, category VARCHAR(50) DEFAULT NULL, quantity INT NOT NULL, min_quantity INT NOT NULL, location VARCHAR(50) DEFAULT NULL, supplier_id INT DEFAULT NULL, UNIQUE INDEX UNIQ_1F1B251E77153098 (code), INDEX IDX_1F1B251E2ADD6D8C (supplier_id), PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('CREATE TABLE supplier (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(100) NOT NULL, contact_person VARCHAR(100) DEFAULT NULL, phone VARCHAR(30) DEFAULT NULL, email VARCHAR(100) DEFAULT NULL, PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('ALTER TABLE item ADD CONSTRAINT FK_1F1B251E2ADD6D8C FOREIGN KEY (supplier_id) REFERENCES supplier (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE item DROP FOREIGN KEY FK_1F1B251E2ADD6D8C');
        $this->addSql('DROP TABLE item');
        $this->addSql('DROP TABLE supplier');
    }
}
