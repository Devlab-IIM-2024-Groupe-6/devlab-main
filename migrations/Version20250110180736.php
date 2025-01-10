<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250110180736 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE device_maintenance ADD tracking_number_id INT NOT NULL, DROP tracking_number');
        $this->addSql('ALTER TABLE device_maintenance ADD CONSTRAINT FK_5D04AC2FBD81AE9F FOREIGN KEY (tracking_number_id) REFERENCES client (id)');
        $this->addSql('CREATE INDEX IDX_5D04AC2FBD81AE9F ON device_maintenance (tracking_number_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE device_maintenance DROP FOREIGN KEY FK_5D04AC2FBD81AE9F');
        $this->addSql('DROP INDEX IDX_5D04AC2FBD81AE9F ON device_maintenance');
        $this->addSql('ALTER TABLE device_maintenance ADD tracking_number VARCHAR(255) NOT NULL, DROP tracking_number_id');
    }
}
