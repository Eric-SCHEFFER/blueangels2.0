<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210105163503 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE categories_image (id INT AUTO_INCREMENT NOT NULL, nom VARCHAR(255) DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE images_event ADD categories_image_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE images_event ADD CONSTRAINT FK_1696EB1BEB5B8C54 FOREIGN KEY (categories_image_id) REFERENCES categories_image (id)');
        $this->addSql('CREATE INDEX IDX_1696EB1BEB5B8C54 ON images_event (categories_image_id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE images_event DROP FOREIGN KEY FK_1696EB1BEB5B8C54');
        $this->addSql('DROP TABLE categories_image');
        $this->addSql('DROP INDEX IDX_1696EB1BEB5B8C54 ON images_event');
        $this->addSql('ALTER TABLE images_event DROP categories_image_id');
    }
}
