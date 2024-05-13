<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240513205306 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE user (id INT AUTO_INCREMENT NOT NULL, email VARCHAR(180) NOT NULL, username VARCHAR(180) NOT NULL, firstname VARCHAR(180) NOT NULL, lastname VARCHAR(180) NOT NULL, created_at DATETIME NOT NULL, updated_at DATETIME NOT NULL, uuid VARCHAR(180) NOT NULL, roles JSON NOT NULL, password VARCHAR(255) NOT NULL, is_verified TINYINT(1) NOT NULL, UNIQUE INDEX UNIQ_IDENTIFIER_UUID (uuid), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE categorie CHANGE name name VARCHAR(255) NOT NULL');
        $this->addSql('ALTER TABLE question CHANGE id_categorie id_categorie INT NOT NULL, CHANGE question question VARCHAR(255) NOT NULL');
        $this->addSql('ALTER TABLE reponse CHANGE id_question id_question INT NOT NULL, CHANGE reponse reponse VARCHAR(255) NOT NULL, CHANGE reponse_expected reponse_expected TINYINT(1) NOT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE user');
        $this->addSql('ALTER TABLE categorie CHANGE name name VARCHAR(100) NOT NULL');
        $this->addSql('ALTER TABLE question CHANGE question question VARCHAR(255) DEFAULT NULL, CHANGE id_categorie id_categorie INT DEFAULT NULL');
        $this->addSql('ALTER TABLE reponse CHANGE id_question id_question INT DEFAULT NULL, CHANGE reponse reponse VARCHAR(255) DEFAULT NULL, CHANGE reponse_expected reponse_expected TINYINT(1) DEFAULT NULL');
    }
}
