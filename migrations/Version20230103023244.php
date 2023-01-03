<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230103023244 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE manager_timesheet CHANGE coming_to_work coming_to_work DATETIME NOT NULL, CHANGE leaving_from_work leaving_from_work DATETIME NOT NULL, CHANGE sheduled_date sheduled_date DATE NOT NULL');
        $this->addSql('ALTER TABLE pay ADD working_days INT NOT NULL, ADD sells_number INT NOT NULL, ADD consultations INT NOT NULL, ADD test_drives INT NOT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE manager_timesheet CHANGE coming_to_work coming_to_work DATETIME DEFAULT NULL, CHANGE leaving_from_work leaving_from_work DATETIME DEFAULT NULL, CHANGE sheduled_date sheduled_date DATE DEFAULT NULL');
        $this->addSql('ALTER TABLE pay DROP working_days, DROP sells_number, DROP consultations, DROP test_drives');
    }
}
