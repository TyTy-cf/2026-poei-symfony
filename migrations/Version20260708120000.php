<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20260708120000 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Drop duplicate country_game join table; game_country is the expected join table.';
    }

    public function up(Schema $schema): void
    {
        if ($schema->hasTable('country_game') && $schema->hasTable('game_country')) {
            $this->addSql('INSERT IGNORE INTO game_country (game_id, country_id) SELECT game_id, country_id FROM country_game');
        }

        $this->addSql('DROP TABLE IF EXISTS country_game');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('CREATE TABLE country_game (country_id INT NOT NULL, game_id INT NOT NULL, INDEX IDX_A418BA68F92F3E70 (country_id), INDEX IDX_A418BA68E48FD905 (game_id), PRIMARY KEY (country_id, game_id)) DEFAULT CHARACTER SET utf8');
        $this->addSql('ALTER TABLE country_game ADD CONSTRAINT FK_A418BA68F92F3E70 FOREIGN KEY (country_id) REFERENCES country (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE country_game ADD CONSTRAINT FK_A418BA68E48FD905 FOREIGN KEY (game_id) REFERENCES game (id) ON DELETE CASCADE');
    }
}
