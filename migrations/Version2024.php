<?php

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;

class Version2024 extends \Doctrine\Migrations\AbstractMigration
{

    /**
     * @inheritDoc
     */
    public function up(Schema $schema): void
    {
        $this->addSql(file_get_contents(dirname(__FILE__).'/fixtures_script/buyer.sql'));
        $this->addSql(file_get_contents(dirname(__FILE__).'/fixtures_script/manager.sql'));
        $this->addSql(file_get_contents(dirname(__FILE__).'/fixtures_script/action_type.sql'));
        $this->addSql(file_get_contents(dirname(__FILE__).'/fixtures_script/car.sql'));
        $this->addSql(file_get_contents(dirname(__FILE__).'/fixtures_script/product.sql'));
        $this->addSql(file_get_contents(dirname(__FILE__).'/fixtures_script/action.sql'));
        $this->addSql(file_get_contents(dirname(__FILE__).'/fixtures_script/manager_timesheet.sql'));

    }
}