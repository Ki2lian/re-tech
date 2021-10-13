<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20211013092257 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE annonce_tag (annonce_id INT NOT NULL, tag_id INT NOT NULL, INDEX IDX_C304E7E28805AB2F (annonce_id), INDEX IDX_C304E7E2BAD26311 (tag_id), PRIMARY KEY(annonce_id, tag_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE ticket (id INT AUTO_INCREMENT NOT NULL, id_compte_id INT NOT NULL, actif TINYINT(1) NOT NULL, contenu LONGTEXT NOT NULL, INDEX IDX_97A0ADA372F0DA07 (id_compte_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE annonce_tag ADD CONSTRAINT FK_C304E7E28805AB2F FOREIGN KEY (annonce_id) REFERENCES annonce (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE annonce_tag ADD CONSTRAINT FK_C304E7E2BAD26311 FOREIGN KEY (tag_id) REFERENCES tag (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE ticket ADD CONSTRAINT FK_97A0ADA372F0DA07 FOREIGN KEY (id_compte_id) REFERENCES `user` (id)');
        $this->addSql('ALTER TABLE annonce ADD id_compte_id INT DEFAULT NULL, ADD actif TINYINT(1) NOT NULL');
        $this->addSql('ALTER TABLE annonce ADD CONSTRAINT FK_F65593E572F0DA07 FOREIGN KEY (id_compte_id) REFERENCES `user` (id)');
        $this->addSql('CREATE INDEX IDX_F65593E572F0DA07 ON annonce (id_compte_id)');
        $this->addSql('ALTER TABLE image ADD id_annonce_id INT DEFAULT NULL, ADD contenu LONGBLOB NOT NULL, ADD jeton VARCHAR(255) NOT NULL, CHANGE presentation presentation TINYINT(1) NOT NULL');
        $this->addSql('ALTER TABLE image ADD CONSTRAINT FK_C53D045F2D8F2BF8 FOREIGN KEY (id_annonce_id) REFERENCES annonce (id)');
        $this->addSql('CREATE INDEX IDX_C53D045F2D8F2BF8 ON image (id_annonce_id)');
        $this->addSql('ALTER TABLE message ADD id_compte_id INT NOT NULL, ADD id_annonce_id INT NOT NULL');
        $this->addSql('ALTER TABLE message ADD CONSTRAINT FK_B6BD307F72F0DA07 FOREIGN KEY (id_compte_id) REFERENCES `user` (id)');
        $this->addSql('ALTER TABLE message ADD CONSTRAINT FK_B6BD307F2D8F2BF8 FOREIGN KEY (id_annonce_id) REFERENCES annonce (id)');
        $this->addSql('CREATE INDEX IDX_B6BD307F72F0DA07 ON message (id_compte_id)');
        $this->addSql('CREATE INDEX IDX_B6BD307F2D8F2BF8 ON message (id_annonce_id)');
        $this->addSql('ALTER TABLE user ADD actif TINYINT(1) NOT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE annonce_tag');
        $this->addSql('DROP TABLE ticket');
        $this->addSql('ALTER TABLE annonce DROP FOREIGN KEY FK_F65593E572F0DA07');
        $this->addSql('DROP INDEX IDX_F65593E572F0DA07 ON annonce');
        $this->addSql('ALTER TABLE annonce DROP id_compte_id, DROP actif');
        $this->addSql('ALTER TABLE image DROP FOREIGN KEY FK_C53D045F2D8F2BF8');
        $this->addSql('DROP INDEX IDX_C53D045F2D8F2BF8 ON image');
        $this->addSql('ALTER TABLE image DROP id_annonce_id, DROP contenu, DROP jeton, CHANGE presentation presentation VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('ALTER TABLE message DROP FOREIGN KEY FK_B6BD307F72F0DA07');
        $this->addSql('ALTER TABLE message DROP FOREIGN KEY FK_B6BD307F2D8F2BF8');
        $this->addSql('DROP INDEX IDX_B6BD307F72F0DA07 ON message');
        $this->addSql('DROP INDEX IDX_B6BD307F2D8F2BF8 ON message');
        $this->addSql('ALTER TABLE message DROP id_compte_id, DROP id_annonce_id');
        $this->addSql('ALTER TABLE `user` DROP actif');
    }
}
