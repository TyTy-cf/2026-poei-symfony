<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20260708100501 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE category (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, image VARCHAR(255) DEFAULT NULL, slug VARCHAR(255) NOT NULL, PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8');
        $this->addSql('CREATE TABLE country (id INT AUTO_INCREMENT NOT NULL, code VARCHAR(5) NOT NULL, name VARCHAR(255) NOT NULL, nationality VARCHAR(255) NOT NULL, slug VARCHAR(255) NOT NULL, url_flag VARCHAR(255) DEFAULT NULL, PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8');
        $this->addSql('CREATE TABLE game (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, price INT NOT NULL, description LONGTEXT DEFAULT NULL, published_at DATETIME NOT NULL, thumbnail_cover VARCHAR(255) DEFAULT NULL, slug VARCHAR(255) NOT NULL, thumbnail_cover_link VARCHAR(255) DEFAULT NULL, publisher_id INT DEFAULT NULL, INDEX IDX_232B318C40C86FCE (publisher_id), PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8');
        $this->addSql('CREATE TABLE publisher (id INT AUTO_INCREMENT NOT NULL, created_at DATETIME NOT NULL, name VARCHAR(255) NOT NULL, slug VARCHAR(255) NOT NULL, website VARCHAR(255) DEFAULT NULL, country_id INT DEFAULT NULL, INDEX IDX_9CE8D546F92F3E70 (country_id), PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8');
        $this->addSql('CREATE TABLE review (id INT AUTO_INCREMENT NOT NULL, content LONGTEXT NOT NULL, created_at DATETIME NOT NULL, downvote INT NOT NULL, upvote INT NOT NULL, rating SMALLINT NOT NULL, user_id INT DEFAULT NULL, game_id INT DEFAULT NULL, INDEX IDX_794381C6A76ED395 (user_id), INDEX IDX_794381C6E48FD905 (game_id), PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8');
        $this->addSql('CREATE TABLE user (id INT AUTO_INCREMENT NOT NULL, email VARCHAR(255) NOT NULL, roles JSON NOT NULL, password VARCHAR(255) NOT NULL, created_at DATETIME NOT NULL, name VARCHAR(255) NOT NULL, nickname VARCHAR(255) NOT NULL, profile_image VARCHAR(255) DEFAULT NULL, wallet INT NOT NULL, country_id INT DEFAULT NULL, INDEX IDX_8D93D649F92F3E70 (country_id), PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8');
        $this->addSql('CREATE TABLE messenger_messages (id BIGINT AUTO_INCREMENT NOT NULL, body LONGTEXT NOT NULL, headers LONGTEXT NOT NULL, queue_name VARCHAR(190) NOT NULL, created_at DATETIME NOT NULL, available_at DATETIME NOT NULL, delivered_at DATETIME DEFAULT NULL, INDEX IDX_75EA56E0FB7336F0E3BD61CE16BA31DBBF396750 (queue_name, available_at, delivered_at, id), PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8');
        $this->addSql('ALTER TABLE game ADD CONSTRAINT FK_232B318C40C86FCE FOREIGN KEY (publisher_id) REFERENCES publisher (id)');
        $this->addSql('ALTER TABLE publisher ADD CONSTRAINT FK_9CE8D546F92F3E70 FOREIGN KEY (country_id) REFERENCES country (id)');
        $this->addSql('ALTER TABLE review ADD CONSTRAINT FK_794381C6A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE review ADD CONSTRAINT FK_794381C6E48FD905 FOREIGN KEY (game_id) REFERENCES game (id)');
        $this->addSql('ALTER TABLE user ADD CONSTRAINT FK_8D93D649F92F3E70 FOREIGN KEY (country_id) REFERENCES country (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE game DROP FOREIGN KEY FK_232B318C40C86FCE');
        $this->addSql('ALTER TABLE publisher DROP FOREIGN KEY FK_9CE8D546F92F3E70');
        $this->addSql('ALTER TABLE review DROP FOREIGN KEY FK_794381C6A76ED395');
        $this->addSql('ALTER TABLE review DROP FOREIGN KEY FK_794381C6E48FD905');
        $this->addSql('ALTER TABLE user DROP FOREIGN KEY FK_8D93D649F92F3E70');
        $this->addSql('DROP TABLE category');
        $this->addSql('DROP TABLE country');
        $this->addSql('DROP TABLE game');
        $this->addSql('DROP TABLE publisher');
        $this->addSql('DROP TABLE review');
        $this->addSql('DROP TABLE user');
        $this->addSql('DROP TABLE messenger_messages');
    }
}
