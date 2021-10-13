<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20211013133423 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE annonce (id INT AUTO_INCREMENT NOT NULL, id_compte_id INT DEFAULT NULL, titre VARCHAR(255) NOT NULL, description VARCHAR(255) NOT NULL, prix INT NOT NULL, annonce_payante VARCHAR(255) NOT NULL, actif TINYINT(1) NOT NULL, date_creation DATETIME NOT NULL, date_modification DATETIME NOT NULL, INDEX IDX_F65593E572F0DA07 (id_compte_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE annonce_tag (annonce_id INT NOT NULL, tag_id INT NOT NULL, INDEX IDX_C304E7E28805AB2F (annonce_id), INDEX IDX_C304E7E2BAD26311 (tag_id), PRIMARY KEY(annonce_id, tag_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE image (id INT AUTO_INCREMENT NOT NULL, id_annonce_id INT DEFAULT NULL, presentation TINYINT(1) NOT NULL, contenu LONGBLOB NOT NULL, jeton VARCHAR(255) NOT NULL, INDEX IDX_C53D045F2D8F2BF8 (id_annonce_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE message (id INT AUTO_INCREMENT NOT NULL, id_compte_id INT NOT NULL, id_annonce_id INT NOT NULL, contenu VARCHAR(255) DEFAULT NULL, date_creation DATETIME NOT NULL, INDEX IDX_B6BD307F72F0DA07 (id_compte_id), INDEX IDX_B6BD307F2D8F2BF8 (id_annonce_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE tag (id INT AUTO_INCREMENT NOT NULL, nom VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE ticket (id INT AUTO_INCREMENT NOT NULL, id_compte_id INT NOT NULL, actif TINYINT(1) NOT NULL, contenu LONGTEXT NOT NULL, date_creation DATETIME NOT NULL, INDEX IDX_97A0ADA372F0DA07 (id_compte_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE `user` (id INT AUTO_INCREMENT NOT NULL, email VARCHAR(255) NOT NULL, roles JSON NOT NULL, password VARCHAR(255) NOT NULL, nom VARCHAR(255) DEFAULT NULL, prenom VARCHAR(255) DEFAULT NULL, pseudo VARCHAR(255) NOT NULL, actif TINYINT(1) NOT NULL, date_creation DATETIME NOT NULL, date_modification DATETIME NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE annonce ADD CONSTRAINT FK_F65593E572F0DA07 FOREIGN KEY (id_compte_id) REFERENCES `user` (id)');
        $this->addSql('ALTER TABLE annonce_tag ADD CONSTRAINT FK_C304E7E28805AB2F FOREIGN KEY (annonce_id) REFERENCES annonce (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE annonce_tag ADD CONSTRAINT FK_C304E7E2BAD26311 FOREIGN KEY (tag_id) REFERENCES tag (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE image ADD CONSTRAINT FK_C53D045F2D8F2BF8 FOREIGN KEY (id_annonce_id) REFERENCES annonce (id)');
        $this->addSql('ALTER TABLE message ADD CONSTRAINT FK_B6BD307F72F0DA07 FOREIGN KEY (id_compte_id) REFERENCES `user` (id)');
        $this->addSql('ALTER TABLE message ADD CONSTRAINT FK_B6BD307F2D8F2BF8 FOREIGN KEY (id_annonce_id) REFERENCES annonce (id)');
        $this->addSql('ALTER TABLE ticket ADD CONSTRAINT FK_97A0ADA372F0DA07 FOREIGN KEY (id_compte_id) REFERENCES `user` (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE annonce_tag DROP FOREIGN KEY FK_C304E7E28805AB2F');
        $this->addSql('ALTER TABLE image DROP FOREIGN KEY FK_C53D045F2D8F2BF8');
        $this->addSql('ALTER TABLE message DROP FOREIGN KEY FK_B6BD307F2D8F2BF8');
        $this->addSql('ALTER TABLE annonce_tag DROP FOREIGN KEY FK_C304E7E2BAD26311');
        $this->addSql('ALTER TABLE annonce DROP FOREIGN KEY FK_F65593E572F0DA07');
        $this->addSql('ALTER TABLE message DROP FOREIGN KEY FK_B6BD307F72F0DA07');
        $this->addSql('ALTER TABLE ticket DROP FOREIGN KEY FK_97A0ADA372F0DA07');
        $this->addSql('DROP TABLE annonce');
        $this->addSql('DROP TABLE annonce_tag');
        $this->addSql('DROP TABLE image');
        $this->addSql('DROP TABLE message');
        $this->addSql('DROP TABLE tag');
        $this->addSql('DROP TABLE ticket');
        $this->addSql('DROP TABLE `user`');
    }
}
