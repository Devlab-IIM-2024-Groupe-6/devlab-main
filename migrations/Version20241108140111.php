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
        // Création de la table deposit
        $this->addSql('CREATE TABLE deposit (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, address VARCHAR(255) NOT NULL, latitude DOUBLE PRECISION NOT NULL, longitude DOUBLE PRECISION NOT NULL, description LONGTEXT DEFAULT NULL, schedules VARCHAR(255) DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');

        // Création de la table user
        $this->addSql('CREATE TABLE user (id INT AUTO_INCREMENT NOT NULL, deposit_id INT DEFAULT NULL, email VARCHAR(180) NOT NULL, roles JSON NOT NULL, password VARCHAR(255) NOT NULL, firstname VARCHAR(255) NOT NULL, lastname VARCHAR(255) NOT NULL, tracking_number VARCHAR(255) DEFAULT NULL, UNIQUE INDEX UNIQ_8D93D649E7927C74 (email), UNIQUE INDEX UNIQ_8D93D6493E1C9C18 (tracking_number), INDEX IDX_8D93D6499815E4B1 (deposit_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE user ADD CONSTRAINT FK_8D93D6499815E4B1 FOREIGN KEY (deposit_id) REFERENCES deposit (id)');

        // Création de la table client
        $this->addSql('CREATE TABLE client (id INT AUTO_INCREMENT NOT NULL, deposit_id INT NOT NULL, email VARCHAR(255) NOT NULL, firstname VARCHAR(255) NOT NULL, lastname VARCHAR(255) NOT NULL, tracking_number VARCHAR(255) NOT NULL, UNIQUE INDEX UNIQ_C7440455E7927C74 (email), UNIQUE INDEX UNIQ_C74404553E1C9C18 (tracking_number), INDEX IDX_C74404559815E4B1 (deposit_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE client ADD CONSTRAINT FK_C74404559815E4B1 FOREIGN KEY (deposit_id) REFERENCES deposit (id)');

        // Création de la table device_maintenance
        $this->addSql('CREATE TABLE device_maintenance (id INT AUTO_INCREMENT NOT NULL, tracking_number VARCHAR(255) NOT NULL, current_step INT NOT NULL, screen TINYINT(1) NOT NULL, oxidation_status TINYINT(1) NOT NULL, hinges TINYINT(1) NOT NULL, fan TINYINT(1) NOT NULL, button TINYINT(1) NOT NULL, sensors TINYINT(1) NOT NULL, chassis TINYINT(1) NOT NULL, data_wipe TINYINT(1) NOT NULL, computer_unlock TINYINT(1) NOT NULL, driver TINYINT(1) NOT NULL, computer_update TINYINT(1) NOT NULL, motherboard TINYINT(1) NOT NULL, networks TINYINT(1) NOT NULL, components TINYINT(1) NOT NULL, battery TINYINT(1) NOT NULL, power_supply TINYINT(1) NOT NULL, INDEX IDX_5D04AC2FBD81AE9F (tracking_number), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE device_maintenance ADD CONSTRAINT FK_5D04AC2FBD81AE9F FOREIGN KEY (tracking_number) REFERENCES client (tracking_number)');
    }

    public function down(Schema $schema): void
    {
        // Suppression des tables dans l'ordre inverse de leur création
        $this->addSql('ALTER TABLE device_maintenance DROP FOREIGN KEY FK_5D04AC2FBD81AE9F');
        $this->addSql('DROP TABLE device_maintenance');

        $this->addSql('ALTER TABLE client DROP FOREIGN KEY FK_C74404559815E4B1');
        $this->addSql('DROP TABLE client');

        $this->addSql('ALTER TABLE user DROP FOREIGN KEY FK_8D93D6499815E4B1');
        $this->addSql('DROP TABLE user');

        $this->addSql('DROP TABLE deposit');
    }
}