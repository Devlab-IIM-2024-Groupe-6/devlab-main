<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20241111141111 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE device_maintenance (id INT AUTO_INCREMENT NOT NULL, user_id INT NOT NULL, tracking_number VARCHAR(255) NOT NULL, current_step INT NOT NULL, screen TINYINT(1) NOT NULL, oxidation_status TINYINT(1) NOT NULL, hinges TINYINT(1) NOT NULL, fan TINYINT(1) NOT NULL, button TINYINT(1) NOT NULL, sensors TINYINT(1) NOT NULL, chassis TINYINT(1) NOT NULL, data_wipe TINYINT(1) NOT NULL, computer_unlock TINYINT(1) NOT NULL, driver TINYINT(1) NOT NULL, computer_update TINYINT(1) NOT NULL, motherboard TINYINT(1) NOT NULL, networks TINYINT(1) NOT NULL, components TINYINT(1) NOT NULL, battery TINYINT(1) NOT NULL, power_supply TINYINT(1) NOT NULL, INDEX IDX_5D04AC2FA76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE device_maintenance ADD CONSTRAINT FK_5D04AC2FA76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE deposit CHANGE latitude latitude DOUBLE PRECISION NOT NULL, CHANGE longitude longitude DOUBLE PRECISION NOT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE device_maintenance DROP FOREIGN KEY FK_5D04AC2FA76ED395');
        $this->addSql('DROP TABLE device_maintenance');
        $this->addSql('ALTER TABLE deposit CHANGE latitude latitude VARCHAR(255) NOT NULL, CHANGE longitude longitude VARCHAR(255) NOT NULL');
    }
}
