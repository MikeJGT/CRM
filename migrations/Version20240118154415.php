<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240118154415 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE incident ADD COLUMN brochure_filename VARCHAR(255) DEFAULT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TEMPORARY TABLE __temp__incident AS SELECT id, description, state, priority, assigned, observations, start_date, finish_date FROM incident');
        $this->addSql('DROP TABLE incident');
        $this->addSql('CREATE TABLE incident (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, description VARCHAR(255) NOT NULL, state VARCHAR(255) NOT NULL, priority VARCHAR(255) NOT NULL, assigned VARCHAR(255) NOT NULL, observations CLOB DEFAULT NULL, start_date DATETIME NOT NULL, finish_date DATETIME DEFAULT NULL)');
        $this->addSql('INSERT INTO incident (id, description, state, priority, assigned, observations, start_date, finish_date) SELECT id, description, state, priority, assigned, observations, start_date, finish_date FROM __temp__incident');
        $this->addSql('DROP TABLE __temp__incident');
    }
}
