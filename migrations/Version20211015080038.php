<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20211015080038 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE wishlist_search_user');
        $this->addSql('ALTER TABLE wishlist_search ADD id_compte_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE wishlist_search ADD CONSTRAINT FK_7C204E172F0DA07 FOREIGN KEY (id_compte_id) REFERENCES `user` (id)');
        $this->addSql('CREATE INDEX IDX_7C204E172F0DA07 ON wishlist_search (id_compte_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE wishlist_search_user (wishlist_search_id INT NOT NULL, user_id INT NOT NULL, INDEX IDX_970A0B83DF671938 (wishlist_search_id), INDEX IDX_970A0B83A76ED395 (user_id), PRIMARY KEY(wishlist_search_id, user_id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('ALTER TABLE wishlist_search_user ADD CONSTRAINT FK_970A0B83A76ED395 FOREIGN KEY (user_id) REFERENCES user (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE wishlist_search_user ADD CONSTRAINT FK_970A0B83DF671938 FOREIGN KEY (wishlist_search_id) REFERENCES wishlist_search (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE wishlist_search DROP FOREIGN KEY FK_7C204E172F0DA07');
        $this->addSql('DROP INDEX IDX_7C204E172F0DA07 ON wishlist_search');
        $this->addSql('ALTER TABLE wishlist_search DROP id_compte_id');
    }
}
