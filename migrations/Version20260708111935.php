<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20260708111935 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE UNIQUE INDEX UNIQ_64C19C1989D9B62 ON category (slug)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_5373C966989D9B62 ON country (slug)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_232B318C989D9B62 ON game (slug)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_9CE8D546989D9B62 ON publisher (slug)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_8D93D649E7927C74 ON user (email)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP INDEX UNIQ_64C19C1989D9B62 ON category');
        $this->addSql('DROP INDEX UNIQ_5373C966989D9B62 ON country');
        $this->addSql('DROP INDEX UNIQ_232B318C989D9B62 ON game');
        $this->addSql('DROP INDEX UNIQ_9CE8D546989D9B62 ON publisher');
        $this->addSql('DROP INDEX UNIQ_8D93D649E7927C74 ON user');
    }
}
