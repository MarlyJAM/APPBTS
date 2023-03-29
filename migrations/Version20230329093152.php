<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230329093152 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE article_blog DROP category_id');
        $this->addSql('ALTER TABLE article_blog ADD CONSTRAINT FK_7057D642BCF5E72D FOREIGN KEY (categorie_id) REFERENCES category (id)');
        $this->addSql('CREATE INDEX IDX_7057D642BCF5E72D ON article_blog (categorie_id)');
        $this->addSql('ALTER TABLE contact ADD subject VARCHAR(255) DEFAULT NULL, ADD created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\'');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE article_blog DROP FOREIGN KEY FK_7057D642BCF5E72D');
        $this->addSql('DROP INDEX IDX_7057D642BCF5E72D ON article_blog');
        $this->addSql('ALTER TABLE article_blog ADD category_id INT NOT NULL');
        $this->addSql('ALTER TABLE contact DROP subject, DROP created_at');
    }
}
