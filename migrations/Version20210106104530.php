<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210106104530 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE images_article ADD articles_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE images_article ADD CONSTRAINT FK_9D4710041EBAF6CC FOREIGN KEY (articles_id) REFERENCES articles (id)');
        $this->addSql('CREATE INDEX IDX_9D4710041EBAF6CC ON images_article (articles_id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE images_article DROP FOREIGN KEY FK_9D4710041EBAF6CC');
        $this->addSql('DROP INDEX IDX_9D4710041EBAF6CC ON images_article');
        $this->addSql('ALTER TABLE images_article DROP articles_id');
    }
}
