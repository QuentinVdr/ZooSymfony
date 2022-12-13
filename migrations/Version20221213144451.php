<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20221213144451 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE animal (id INT AUTO_INCREMENT NOT NULL, enclos_id INT NOT NULL, identification VARCHAR(14) NOT NULL, nom VARCHAR(255) DEFAULT NULL, date_naissance DATE DEFAULT NULL, date_arrivee DATE NOT NULL, date_depart DATE DEFAULT NULL, zoo_proprietaire TINYINT(1) NOT NULL, genre VARCHAR(255) NOT NULL, espece VARCHAR(255) NOT NULL, sexe VARCHAR(255) DEFAULT NULL, sterile TINYINT(1) NOT NULL, quarantaine TINYINT(1) NOT NULL, INDEX IDX_6AAB231FB1C0859 (enclos_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE animal ADD CONSTRAINT FK_6AAB231FB1C0859 FOREIGN KEY (enclos_id) REFERENCES enclos (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE animal DROP FOREIGN KEY FK_6AAB231FB1C0859');
        $this->addSql('DROP TABLE animal');
    }
}
