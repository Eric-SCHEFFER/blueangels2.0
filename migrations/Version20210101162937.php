<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210101162937 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE images_events ADD images_categorie_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE images_events ADD CONSTRAINT FK_917D900C45318E7A FOREIGN KEY (images_categorie_id) REFERENCES images_categories (id)');
        $this->addSql('CREATE INDEX IDX_917D900C45318E7A ON images_events (images_categorie_id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE images_events DROP FOREIGN KEY FK_917D900C45318E7A');
        $this->addSql('DROP INDEX IDX_917D900C45318E7A ON images_events');
        $this->addSql('ALTER TABLE images_events DROP images_categorie_id');
    }
}
