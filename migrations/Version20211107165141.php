<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20211107165141 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE annonce DROP FOREIGN KEY FK_F65593E572F0DA07');
        $this->addSql('ALTER TABLE annonce ADD CONSTRAINT FK_F65593E572F0DA07 FOREIGN KEY (id_compte_id) REFERENCES `user` (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE image DROP FOREIGN KEY FK_C53D045F2D8F2BF8');
        $this->addSql('ALTER TABLE image ADD CONSTRAINT FK_C53D045F2D8F2BF8 FOREIGN KEY (id_annonce_id) REFERENCES annonce (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE message DROP FOREIGN KEY FK_B6BD307F2D8F2BF8');
        $this->addSql('ALTER TABLE message ADD CONSTRAINT FK_B6BD307F2D8F2BF8 FOREIGN KEY (id_annonce_id) REFERENCES annonce (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE ticket DROP FOREIGN KEY FK_97A0ADA372F0DA07');
        $this->addSql('ALTER TABLE ticket ADD CONSTRAINT FK_97A0ADA372F0DA07 FOREIGN KEY (id_compte_id) REFERENCES `user` (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE annonce DROP FOREIGN KEY FK_F65593E572F0DA07');
        $this->addSql('ALTER TABLE annonce ADD CONSTRAINT FK_F65593E572F0DA07 FOREIGN KEY (id_compte_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE image DROP FOREIGN KEY FK_C53D045F2D8F2BF8');
        $this->addSql('ALTER TABLE image ADD CONSTRAINT FK_C53D045F2D8F2BF8 FOREIGN KEY (id_annonce_id) REFERENCES annonce (id)');
        $this->addSql('ALTER TABLE message DROP FOREIGN KEY FK_B6BD307F2D8F2BF8');
        $this->addSql('ALTER TABLE message ADD CONSTRAINT FK_B6BD307F2D8F2BF8 FOREIGN KEY (id_annonce_id) REFERENCES annonce (id)');
        $this->addSql('ALTER TABLE ticket DROP FOREIGN KEY FK_97A0ADA372F0DA07');
        $this->addSql('ALTER TABLE ticket ADD CONSTRAINT FK_97A0ADA372F0DA07 FOREIGN KEY (id_compte_id) REFERENCES user (id)');
    }
}
