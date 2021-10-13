<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20211013093436 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE annonce ADD date_creation DATETIME NOT NULL, ADD date_modification DATETIME NOT NULL');
        $this->addSql('ALTER TABLE message ADD date_creation DATETIME NOT NULL');
        $this->addSql('ALTER TABLE ticket ADD date_creation DATETIME NOT NULL');
        $this->addSql('ALTER TABLE user ADD date_creation DATETIME NOT NULL, ADD date_modification DATETIME NOT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE annonce DROP date_creation, DROP date_modification');
        $this->addSql('ALTER TABLE message DROP date_creation');
        $this->addSql('ALTER TABLE ticket DROP date_creation');
        $this->addSql('ALTER TABLE `user` DROP date_creation, DROP date_modification');
    }
}
