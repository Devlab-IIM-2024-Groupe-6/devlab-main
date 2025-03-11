<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250110152617 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Consolidated migration for Deposit, User, Client, and DeviceMaintenance entities';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE client (id INT AUTO_INCREMENT NOT NULL, email VARCHAR(255) NOT NULL, firstname VARCHAR(255) NOT NULL, lastname VARCHAR(255) NOT NULL, UNIQUE INDEX UNIQ_C7440455E7927C74 (email), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE deposit (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, address VARCHAR(255) NOT NULL, latitude DOUBLE PRECISION NOT NULL, longitude DOUBLE PRECISION NOT NULL, description LONGTEXT DEFAULT NULL, schedules VARCHAR(255) DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE device_maintenance (id INT AUTO_INCREMENT NOT NULL, current_step_id INT DEFAULT NULL, client_id INT NOT NULL, deposit_id INT NOT NULL, screen TINYINT(1) DEFAULT NULL, oxidation_status TINYINT(1) DEFAULT NULL, hinges TINYINT(1) DEFAULT NULL, fan TINYINT(1) DEFAULT NULL, button TINYINT(1) DEFAULT NULL, sensors TINYINT(1) DEFAULT NULL, chassis TINYINT(1) DEFAULT NULL, data_wipe TINYINT(1) DEFAULT NULL, computer_unlock TINYINT(1) DEFAULT NULL, driver TINYINT(1) DEFAULT NULL, computer_update TINYINT(1) DEFAULT NULL, motherboard TINYINT(1) DEFAULT NULL, networks TINYINT(1) DEFAULT NULL, components TINYINT(1) DEFAULT NULL, battery TINYINT(1) DEFAULT NULL, power_supply TINYINT(1) DEFAULT NULL, tracking_number VARCHAR(255) DEFAULT NULL, INDEX IDX_5D04AC2FD9BF9B19 (current_step_id), INDEX IDX_5D04AC2F19EB6921 (client_id), INDEX IDX_5D04AC2F9815E4B1 (deposit_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE device_maintenance_log (id INT AUTO_INCREMENT NOT NULL, device_maintenance_id INT NOT NULL, previous_step_id INT DEFAULT NULL, current_step_id INT NOT NULL, next_step_id INT DEFAULT NULL, changed_at DATETIME NOT NULL, INDEX IDX_9BD1821E508FF34C (device_maintenance_id), INDEX IDX_9BD1821E4229744F (previous_step_id), INDEX IDX_9BD1821ED9BF9B19 (current_step_id), INDEX IDX_9BD1821EB13C343E (next_step_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE maintenance_step (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, description LONGTEXT DEFAULT NULL, step_order INT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE user (id INT AUTO_INCREMENT NOT NULL, deposit_id INT DEFAULT NULL, email VARCHAR(180) NOT NULL, roles JSON NOT NULL COMMENT \'(DC2Type:json)\', password VARCHAR(255) NOT NULL, firstname VARCHAR(255) NOT NULL, lastname VARCHAR(255) NOT NULL, UNIQUE INDEX UNIQ_8D93D649E7927C74 (email), INDEX IDX_8D93D6499815E4B1 (deposit_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE device_maintenance ADD CONSTRAINT FK_5D04AC2FD9BF9B19 FOREIGN KEY (current_step_id) REFERENCES maintenance_step (id)');
        $this->addSql('ALTER TABLE device_maintenance ADD CONSTRAINT FK_5D04AC2F19EB6921 FOREIGN KEY (client_id) REFERENCES client (id)');
        $this->addSql('ALTER TABLE device_maintenance ADD CONSTRAINT FK_5D04AC2F9815E4B1 FOREIGN KEY (deposit_id) REFERENCES deposit (id)');
        $this->addSql('ALTER TABLE device_maintenance_log ADD CONSTRAINT FK_9BD1821E508FF34C FOREIGN KEY (device_maintenance_id) REFERENCES device_maintenance (id)');
        $this->addSql('ALTER TABLE device_maintenance_log ADD CONSTRAINT FK_9BD1821E4229744F FOREIGN KEY (previous_step_id) REFERENCES maintenance_step (id)');
        $this->addSql('ALTER TABLE device_maintenance_log ADD CONSTRAINT FK_9BD1821ED9BF9B19 FOREIGN KEY (current_step_id) REFERENCES maintenance_step (id)');
        $this->addSql('ALTER TABLE device_maintenance_log ADD CONSTRAINT FK_9BD1821EB13C343E FOREIGN KEY (next_step_id) REFERENCES maintenance_step (id)');
        $this->addSql('ALTER TABLE user ADD CONSTRAINT FK_8D93D6499815E4B1 FOREIGN KEY (deposit_id) REFERENCES deposit (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE device_maintenance DROP FOREIGN KEY FK_5D04AC2FD9BF9B19');
        $this->addSql('ALTER TABLE device_maintenance DROP FOREIGN KEY FK_5D04AC2F19EB6921');
        $this->addSql('ALTER TABLE device_maintenance DROP FOREIGN KEY FK_5D04AC2F9815E4B1');
        $this->addSql('ALTER TABLE device_maintenance_log DROP FOREIGN KEY FK_9BD1821E508FF34C');
        $this->addSql('ALTER TABLE device_maintenance_log DROP FOREIGN KEY FK_9BD1821E4229744F');
        $this->addSql('ALTER TABLE device_maintenance_log DROP FOREIGN KEY FK_9BD1821ED9BF9B19');
        $this->addSql('ALTER TABLE device_maintenance_log DROP FOREIGN KEY FK_9BD1821EB13C343E');
        $this->addSql('ALTER TABLE user DROP FOREIGN KEY FK_8D93D6499815E4B1');
        $this->addSql('DROP TABLE client');
        $this->addSql('DROP TABLE deposit');
        $this->addSql('DROP TABLE device_maintenance');
        $this->addSql('DROP TABLE device_maintenance_log');
        $this->addSql('DROP TABLE maintenance_step');
        $this->addSql('DROP TABLE user');
    }
}