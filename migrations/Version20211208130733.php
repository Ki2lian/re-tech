<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20211208130733 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE conversation (id INT AUTO_INCREMENT NOT NULL, compte_id INT NOT NULL, compte2_id INT NOT NULL, annonce_id INT NOT NULL, date_creation DATETIME NOT NULL, INDEX IDX_8A8E26E9F2C56620 (compte_id), INDEX IDX_8A8E26E9FF4567D3 (compte2_id), INDEX IDX_8A8E26E98805AB2F (annonce_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE conversation ADD CONSTRAINT FK_8A8E26E9F2C56620 FOREIGN KEY (compte_id) REFERENCES `user` (id)');
        $this->addSql('ALTER TABLE conversation ADD CONSTRAINT FK_8A8E26E9FF4567D3 FOREIGN KEY (compte2_id) REFERENCES `user` (id)');
        $this->addSql('ALTER TABLE conversation ADD CONSTRAINT FK_8A8E26E98805AB2F FOREIGN KEY (annonce_id) REFERENCES annonce (id)');
        $this->addSql('ALTER TABLE message DROP FOREIGN KEY FK_B6BD307F2D8F2BF8');
        $this->addSql('ALTER TABLE message DROP FOREIGN KEY FK_B6BD307F72F0DA07');
        $this->addSql('DROP INDEX IDX_B6BD307F72F0DA07 ON message');
        $this->addSql('DROP INDEX IDX_B6BD307F2D8F2BF8 ON message');
        $this->addSql('ALTER TABLE message ADD conversation_id INT NOT NULL, ADD is_receipt TINYINT(1) NOT NULL, DROP id_compte_id, DROP id_annonce_id');
        $this->addSql('ALTER TABLE message ADD CONSTRAINT FK_B6BD307F9AC0396 FOREIGN KEY (conversation_id) REFERENCES conversation (id)');
        $this->addSql('CREATE INDEX IDX_B6BD307F9AC0396 ON message (conversation_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE message DROP FOREIGN KEY FK_B6BD307F9AC0396');
        $this->addSql('DROP TABLE conversation');
        $this->addSql('DROP INDEX IDX_B6BD307F9AC0396 ON message');
        $this->addSql('ALTER TABLE message ADD id_annonce_id INT NOT NULL, DROP is_receipt, CHANGE conversation_id id_compte_id INT NOT NULL');
        $this->addSql('ALTER TABLE message ADD CONSTRAINT FK_B6BD307F2D8F2BF8 FOREIGN KEY (id_annonce_id) REFERENCES annonce (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE message ADD CONSTRAINT FK_B6BD307F72F0DA07 FOREIGN KEY (id_compte_id) REFERENCES user (id)');
        $this->addSql('CREATE INDEX IDX_B6BD307F72F0DA07 ON message (id_compte_id)');
        $this->addSql('CREATE INDEX IDX_B6BD307F2D8F2BF8 ON message (id_annonce_id)');
    }
}
