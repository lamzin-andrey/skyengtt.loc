<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

//Миграция заполнения тестовыми данными таблицы локаций

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20151202122012 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        //Заполняем таблицу countries
        $this->addSql("INSERT INTO country
        (`id`, `name`, `is_deleted`, `is_accepted`) VALUES
        ('1', 'Россия', 0, 1)");

        $this->addSql("INSERT INTO region
        (`id`, `country_id`, `name`, `is_deleted`, `is_accepted`) VALUES
        (1, 1, 'Московская область', 0, 1)");

        $this->addSql("INSERT INTO city
        (`id`, `region_id`, `name`, `is_deleted`, `is_accepted`) VALUES
        (1, 1, 'Москва', 0, 1)");
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql("TRUNCATE TABLE country");
        $this->addSql("TRUNCATE TABLE region");
        $this->addSql("TRUNCATE TABLE city");
    }
}
