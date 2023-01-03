<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230102031519 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE action (id INT AUTO_INCREMENT NOT NULL, buyer_id INT DEFAULT NULL, manager_id INT DEFAULT NULL, product_id INT DEFAULT NULL, action_type_id INT DEFAULT NULL, date DATETIME NOT NULL, INDEX IDX_47CC8C926C755722 (buyer_id), INDEX IDX_47CC8C92783E3463 (manager_id), INDEX IDX_47CC8C924584665A (product_id), INDEX IDX_47CC8C921FEE0472 (action_type_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE action_type (id INT AUTO_INCREMENT NOT NULL, action_type_name VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE buyer (id INT AUTO_INCREMENT NOT NULL, passport_number VARCHAR(255) NOT NULL, passport_series VARCHAR(255) NOT NULL, fio VARCHAR(255) NOT NULL, address VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE car (id INT AUTO_INCREMENT NOT NULL, brand VARCHAR(255) NOT NULL, model VARCHAR(255) NOT NULL, color VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE fine_and_bonus (id INT AUTO_INCREMENT NOT NULL, manager_id INT DEFAULT NULL, amount DOUBLE PRECISION NOT NULL, date_of_start DATE NOT NULL, date_of_end DATE NOT NULL, INDEX IDX_D1DF489A783E3463 (manager_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE manager (id INT AUTO_INCREMENT NOT NULL, manager_name VARCHAR(255) NOT NULL, hourly_cost DOUBLE PRECISION NOT NULL, percentage_comission DOUBLE PRECISION NOT NULL, manager_passport_series VARCHAR(255) NOT NULL, manager_passport_number VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE manager_timesheet (id INT AUTO_INCREMENT NOT NULL, manager_id INT DEFAULT NULL, coming_to_work DATETIME DEFAULT NULL, leaving_from_work DATETIME DEFAULT NULL, sheduled_date DATE DEFAULT NULL, INDEX IDX_3D8F27ED783E3463 (manager_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE pay (id INT AUTO_INCREMENT NOT NULL, manager_id INT DEFAULT NULL, start_of_period DATE NOT NULL, end_of_period DATE NOT NULL, amount DOUBLE PRECISION NOT NULL, refresh_date DATETIME NOT NULL, INDEX IDX_FE8F223C783E3463 (manager_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE product (id INT AUTO_INCREMENT NOT NULL, car_id INT DEFAULT NULL, availability_number INT NOT NULL, year_production INT NOT NULL, INDEX IDX_D34A04ADC3C6F69F (car_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE messenger_messages (id BIGINT AUTO_INCREMENT NOT NULL, body LONGTEXT NOT NULL, headers LONGTEXT NOT NULL, queue_name VARCHAR(190) NOT NULL, created_at DATETIME NOT NULL, available_at DATETIME NOT NULL, delivered_at DATETIME DEFAULT NULL, INDEX IDX_75EA56E0FB7336F0 (queue_name), INDEX IDX_75EA56E0E3BD61CE (available_at), INDEX IDX_75EA56E016BA31DB (delivered_at), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE action ADD CONSTRAINT FK_47CC8C926C755722 FOREIGN KEY (buyer_id) REFERENCES buyer (id)');
        $this->addSql('ALTER TABLE action ADD CONSTRAINT FK_47CC8C92783E3463 FOREIGN KEY (manager_id) REFERENCES manager (id)');
        $this->addSql('ALTER TABLE action ADD CONSTRAINT FK_47CC8C924584665A FOREIGN KEY (product_id) REFERENCES product (id)');
        $this->addSql('ALTER TABLE action ADD CONSTRAINT FK_47CC8C921FEE0472 FOREIGN KEY (action_type_id) REFERENCES action_type (id)');
        $this->addSql('ALTER TABLE fine_and_bonus ADD CONSTRAINT FK_D1DF489A783E3463 FOREIGN KEY (manager_id) REFERENCES manager (id)');
        $this->addSql('ALTER TABLE manager_timesheet ADD CONSTRAINT FK_3D8F27ED783E3463 FOREIGN KEY (manager_id) REFERENCES manager (id)');
        $this->addSql('ALTER TABLE pay ADD CONSTRAINT FK_FE8F223C783E3463 FOREIGN KEY (manager_id) REFERENCES manager (id)');
        $this->addSql('ALTER TABLE product ADD CONSTRAINT FK_D34A04ADC3C6F69F FOREIGN KEY (car_id) REFERENCES car (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE action DROP FOREIGN KEY FK_47CC8C926C755722');
        $this->addSql('ALTER TABLE action DROP FOREIGN KEY FK_47CC8C92783E3463');
        $this->addSql('ALTER TABLE action DROP FOREIGN KEY FK_47CC8C924584665A');
        $this->addSql('ALTER TABLE action DROP FOREIGN KEY FK_47CC8C921FEE0472');
        $this->addSql('ALTER TABLE fine_and_bonus DROP FOREIGN KEY FK_D1DF489A783E3463');
        $this->addSql('ALTER TABLE manager_timesheet DROP FOREIGN KEY FK_3D8F27ED783E3463');
        $this->addSql('ALTER TABLE pay DROP FOREIGN KEY FK_FE8F223C783E3463');
        $this->addSql('ALTER TABLE product DROP FOREIGN KEY FK_D34A04ADC3C6F69F');
        $this->addSql('DROP TABLE action');
        $this->addSql('DROP TABLE action_type');
        $this->addSql('DROP TABLE buyer');
        $this->addSql('DROP TABLE car');
        $this->addSql('DROP TABLE fine_and_bonus');
        $this->addSql('DROP TABLE manager');
        $this->addSql('DROP TABLE manager_timesheet');
        $this->addSql('DROP TABLE pay');
        $this->addSql('DROP TABLE product');
        $this->addSql('DROP TABLE messenger_messages');
    }
}
