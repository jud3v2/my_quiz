<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240514182307 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE user_history (id INT AUTO_INCREMENT NOT NULL, user_id INT NOT NULL, quizz_id INT NOT NULL, score INT NOT NULL, total INT NOT NULL, INDEX IDX_7FB76E41A76ED395 (user_id), UNIQUE INDEX UNIQ_7FB76E41BA934BCD (quizz_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE user_reponse (id INT AUTO_INCREMENT NOT NULL, user_id INT NOT NULL, history_id INT NOT NULL, question_id INT NOT NULL, expected VARCHAR(255) NOT NULL, answer VARCHAR(255) NOT NULL, INDEX IDX_7BBC0CDA76ED395 (user_id), INDEX IDX_7BBC0CD1E058452 (history_id), UNIQUE INDEX UNIQ_7BBC0CD1E27F6BF (question_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE user_history ADD CONSTRAINT FK_7FB76E41A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE user_history ADD CONSTRAINT FK_7FB76E41BA934BCD FOREIGN KEY (quizz_id) REFERENCES categorie (id)');
        $this->addSql('ALTER TABLE user_reponse ADD CONSTRAINT FK_7BBC0CDA76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE user_reponse ADD CONSTRAINT FK_7BBC0CD1E058452 FOREIGN KEY (history_id) REFERENCES user_history (id)');
        $this->addSql('ALTER TABLE user_reponse ADD CONSTRAINT FK_7BBC0CD1E27F6BF FOREIGN KEY (question_id) REFERENCES question (id)');
        $this->addSql('ALTER TABLE user CHANGE created_at created_at DATETIME NOT NULL, CHANGE updated_at updated_at DATETIME NOT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE user_history DROP FOREIGN KEY FK_7FB76E41A76ED395');
        $this->addSql('ALTER TABLE user_history DROP FOREIGN KEY FK_7FB76E41BA934BCD');
        $this->addSql('ALTER TABLE user_reponse DROP FOREIGN KEY FK_7BBC0CDA76ED395');
        $this->addSql('ALTER TABLE user_reponse DROP FOREIGN KEY FK_7BBC0CD1E058452');
        $this->addSql('ALTER TABLE user_reponse DROP FOREIGN KEY FK_7BBC0CD1E27F6BF');
        $this->addSql('DROP TABLE user_history');
        $this->addSql('DROP TABLE user_reponse');
        $this->addSql('ALTER TABLE user CHANGE created_at created_at DATETIME DEFAULT \'now()\' NOT NULL, CHANGE updated_at updated_at DATETIME DEFAULT CURRENT_TIMESTAMP NOT NULL');
    }
}
