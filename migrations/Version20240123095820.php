<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240123095820 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE brochure (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, incident_id INTEGER NOT NULL, file_name VARCHAR(255) NOT NULL, CONSTRAINT FK_2752F24B59E53FB9 FOREIGN KEY (incident_id) REFERENCES incident (id) NOT DEFERRABLE INITIALLY IMMEDIATE)');
        $this->addSql('CREATE INDEX IDX_2752F24B59E53FB9 ON brochure (incident_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE brochure');
    }
}
