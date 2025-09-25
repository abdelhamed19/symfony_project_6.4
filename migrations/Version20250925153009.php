<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250925153009 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE `user` (id INT AUTO_INCREMENT NOT NULL, email VARCHAR(180) NOT NULL, name VARCHAR(255) NOT NULL, roles JSON NOT NULL COMMENT \'(DC2Type:json)\', password VARCHAR(255) NOT NULL, UNIQUE INDEX UNIQ_IDENTIFIER_EMAIL (email), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('DROP INDEX IDX_ARTICLE_TRANSLATION_LOCALE ON article_translation');
        $this->addSql('ALTER TABLE article_translation DROP FOREIGN KEY FK_ARTICLE_TRANSLATION');
        $this->addSql('ALTER TABLE article_translation CHANGE translatable_id translatable_id INT DEFAULT NULL, CHANGE locale locale VARCHAR(5) NOT NULL');
        $this->addSql('DROP INDEX uniq_article_translation ON article_translation');
        $this->addSql('CREATE UNIQUE INDEX article_translation_unique_translation ON article_translation (translatable_id, locale)');
        $this->addSql('ALTER TABLE article_translation ADD CONSTRAINT FK_ARTICLE_TRANSLATION FOREIGN KEY (translatable_id) REFERENCES article (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE `user`');
        $this->addSql('ALTER TABLE article_translation DROP FOREIGN KEY FK_2EEA2F082C2AC5D3');
        $this->addSql('ALTER TABLE article_translation CHANGE translatable_id translatable_id INT NOT NULL, CHANGE locale locale VARCHAR(10) NOT NULL');
        $this->addSql('CREATE INDEX IDX_ARTICLE_TRANSLATION_LOCALE ON article_translation (locale)');
        $this->addSql('DROP INDEX article_translation_unique_translation ON article_translation');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_ARTICLE_TRANSLATION ON article_translation (translatable_id, locale)');
        $this->addSql('ALTER TABLE article_translation ADD CONSTRAINT FK_2EEA2F082C2AC5D3 FOREIGN KEY (translatable_id) REFERENCES article (id) ON DELETE CASCADE');
    }
}
