<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20211015081558 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE transaction (id INT AUTO_INCREMENT NOT NULL, id_compte_id INT NOT NULL, id_annonce_id INT NOT NULL, date_creation DATETIME NOT NULL, moyen_paiement VARCHAR(255) NOT NULL, INDEX IDX_723705D172F0DA07 (id_compte_id), UNIQUE INDEX UNIQ_723705D12D8F2BF8 (id_annonce_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE wishlist (id INT AUTO_INCREMENT NOT NULL, id_compte_id INT NOT NULL, id_annonce_id INT NOT NULL, date_creation DATETIME NOT NULL, date_modif_annonce DATETIME NOT NULL, INDEX IDX_9CE12A3172F0DA07 (id_compte_id), INDEX IDX_9CE12A312D8F2BF8 (id_annonce_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE wishlist_search (id INT AUTO_INCREMENT NOT NULL, id_compte_id INT DEFAULT NULL, INDEX IDX_7C204E172F0DA07 (id_compte_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE wishlist_search_tag (wishlist_search_id INT NOT NULL, tag_id INT NOT NULL, INDEX IDX_40800097DF671938 (wishlist_search_id), INDEX IDX_40800097BAD26311 (tag_id), PRIMARY KEY(wishlist_search_id, tag_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE transaction ADD CONSTRAINT FK_723705D172F0DA07 FOREIGN KEY (id_compte_id) REFERENCES `user` (id)');
        $this->addSql('ALTER TABLE transaction ADD CONSTRAINT FK_723705D12D8F2BF8 FOREIGN KEY (id_annonce_id) REFERENCES annonce (id)');
        $this->addSql('ALTER TABLE wishlist ADD CONSTRAINT FK_9CE12A3172F0DA07 FOREIGN KEY (id_compte_id) REFERENCES `user` (id)');
        $this->addSql('ALTER TABLE wishlist ADD CONSTRAINT FK_9CE12A312D8F2BF8 FOREIGN KEY (id_annonce_id) REFERENCES annonce (id)');
        $this->addSql('ALTER TABLE wishlist_search ADD CONSTRAINT FK_7C204E172F0DA07 FOREIGN KEY (id_compte_id) REFERENCES `user` (id)');
        $this->addSql('ALTER TABLE wishlist_search_tag ADD CONSTRAINT FK_40800097DF671938 FOREIGN KEY (wishlist_search_id) REFERENCES wishlist_search (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE wishlist_search_tag ADD CONSTRAINT FK_40800097BAD26311 FOREIGN KEY (tag_id) REFERENCES tag (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE user CHANGE roles roles JSON NOT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE wishlist_search_tag DROP FOREIGN KEY FK_40800097DF671938');
        $this->addSql('DROP TABLE transaction');
        $this->addSql('DROP TABLE wishlist');
        $this->addSql('DROP TABLE wishlist_search');
        $this->addSql('DROP TABLE wishlist_search_tag');
        $this->addSql('ALTER TABLE `user` CHANGE roles roles VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`');
    }
}
