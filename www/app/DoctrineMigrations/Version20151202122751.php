<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;


//Миграция заполнения тестовыми данными таблицы building

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20151202122751 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        //Заполняем таблицу rubric
        // Вечерние огни: Досуг / Развлечения / Общественное питание / Кафе
        $this->addSql("INSERT INTO rubric
        (`id`, `name`, `parent_id`, `is_deleted`, `is_accepted`) VALUES
        (1, 'Досуг', 0, 0, 1)");

        $this->addSql("INSERT INTO rubric
        (`id`, `name`, `parent_id`, `is_deleted`, `is_accepted`) VALUES
        (2, 'Развлечения', 1, 0, 1)");

        $this->addSql("INSERT INTO rubric
        (`id`, `name`, `parent_id`, `is_deleted`, `is_accepted`) VALUES
        (3, 'Общественное питание', 2, 0, 1)");

        // Автошкола Перспектива: Образование / Работа / Карьера / Автошколы
        $this->addSql("INSERT INTO rubric
        (`id`, `name`, `parent_id`, `is_deleted`, `is_accepted`) VALUES
        (4, 'Образование', 0, 0, 1)");

        $this->addSql("INSERT INTO rubric
        (`id`, `name`, `parent_id`, `is_deleted`, `is_accepted`) VALUES
        (5, 'Работа', 4, 0, 1)");

        $this->addSql("INSERT INTO rubric
        (`id`, `name`, `parent_id`, `is_deleted`, `is_accepted`) VALUES
        (6, 'Карьера', 5, 0, 1)");

        $this->addSql("INSERT INTO rubric
        (`id`, `name`, `parent_id`, `is_deleted`, `is_accepted`) VALUES
        (7, 'Автошколы', 6, 0, 1)");

        //Хороший маркет: Торговые комплексы / Супермаркеты
        $this->addSql("INSERT INTO rubric
        (`id`, `name`, `parent_id`, `is_deleted`, `is_accepted`) VALUES
        (8, 'Торговые комплексы', 0, 0, 1)");

        $this->addSql("INSERT INTO rubric
        (`id`, `name`, `parent_id`, `is_deleted`, `is_accepted`) VALUES
        (9, 'Супермаркеты', 8, 0, 1)");

        $this->addSql("INSERT INTO rubric
        (`id`, `name`, `parent_id`, `is_deleted`, `is_accepted`) VALUES
        (10, 'Кафе', 3, 0, 1)");

        $this->addSql("INSERT INTO rubric
        (`id`, `name`, `parent_id`, `is_deleted`, `is_accepted`) VALUES
        (11, 'Кафе-кондитерские / Кофейни', 3, 0, 1)");

        // Автосервис / Автотовары / Автозапчасти для иномарок
        $this->addSql("INSERT INTO rubric
        (`id`, `name`, `parent_id`, `is_deleted`, `is_accepted`) VALUES
        (12, 'Автосервис', 0, 0, 1)");

        $this->addSql("INSERT INTO rubric
        (`id`, `name`, `parent_id`, `is_deleted`, `is_accepted`) VALUES
        (13, 'Автотовары', 12, 0, 1)");

        $this->addSql("INSERT INTO rubric
        (`id`, `name`, `parent_id`, `is_deleted`, `is_accepted`) VALUES
        (14, 'Автозапчасти для иномарок', 13, 0, 1)");

        // Реклама / Размещение рекламы в интернете
        $this->addSql("INSERT INTO rubric
        (`id`, `name`, `parent_id`, `is_deleted`, `is_accepted`) VALUES
        (15, 'Реклама', 0, 0, 1)");

        $this->addSql("INSERT INTO rubric
        (`id`, `name`, `parent_id`, `is_deleted`, `is_accepted`) VALUES
        (16, 'Размещение рекламы в интернете', 15, 0, 1)");

        // Торговля / Оптовая торговля
        $this->addSql("INSERT INTO rubric
        (`id`, `name`, `parent_id`, `is_deleted`, `is_accepted`) VALUES
        (17, 'Торговля', 0, 0, 1)");

        $this->addSql("INSERT INTO rubric
        (`id`, `name`, `parent_id`, `is_deleted`, `is_accepted`) VALUES
        (18, 'Оптовая торговля', 17, 0, 1)");

        //Заполняем таблицу building
        $this->addSql("INSERT INTO building
        (`id`, `city_id`, `corps`, `home`, `latitude`, `longitude`, `street`,  `is_deleted`, `is_accepted`) VALUES
        (1, 1, 1, 5, 55.858584, 37.659838, 'Енисейская',  0, 1)");

        $this->addSql("INSERT INTO building
        (`id`, `city_id`, `corps`, `home`, `latitude`, `longitude`, `street`,  `is_deleted`, `is_accepted`) VALUES
        (2, 1, 1, 59, 55.896832, 37.672486, 'Осташковское шоссе',  0, 1)");

        $this->addSql("INSERT INTO building
        (`id`, `city_id`, `corps`, `home`, `latitude`, `longitude`, `street`,  `is_deleted`, `is_accepted`) VALUES
        (3, 1, 2, 16, 55.774254, 37.546525, 'Хорошёвское шоссе',  0, 1)");


        //Заполняем таблицу фирм Енисейской 5
        $this->_addFirm(1, 1, 'Вечерние огни', [89032220909], [10]);
        $this->_addFirm(2, 1, 'Перспектива', [84955420601, 84955146887], [7]);
        $this->_addFirm(3, 1, 'Хороший маркет', [33311112222], [9]);

        //Заполняем таблицу фирм Осташковское шоссе 59
        $this->_addFirm(4, 2, 'Азбука вкуса', [3213211245], [9]);
        $this->_addFirm(5, 2, 'Шоколадница', [84957830989], [11]);
        $this->_addFirm(6, 2, 'Лидер 3', [84952216823, 84957623848], [14]);

        //Заполняем таблицу фирм Хорошёвское шоссе, 16 к 2
        $this->_addFirm(7, 3, 'Тренд ООО', [22222221111], [18]);
        $this->_addFirm(8, 3, 'Adverine центр интернет безопасности', [84955404482], [16]);
        $this->_addFirm(9, 3, 'Internet Horizons центр интернет безопасности', [84955404482], [16]);

    }

    private function _addFirm($id, $building_id, $name, array $phones, array $rubrics) {
        //Заполняем таблицу фирм
        $this->addSql("INSERT INTO firm
        (`id`, `building_id`, `name`, `is_deleted`, `is_accepted`) VALUES
        ({$id}, {$building_id}, '{$name}',  0, 1)");

        //телефоны фирмы
        foreach ($phones as $phone) {
            $this->addSql("INSERT INTO firm_phone
            (`firm_id`, `phone`, `is_deleted`, `is_accepted`) VALUES
            ({$id}, {$phone},  0, 1)");
        }
        //Поместить в рубрикатор
        foreach ($rubrics as $r) {
            $this->addSql("INSERT INTO firm_rubric_bind
            (`firm_id`, `rubric_id`, `is_deleted`, `is_accepted`) VALUES
            ({$id}, {$r}, 0, 1)");
        }
    }


    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql("TRUNCATE TABLE firm;");
        $this->addSql("TRUNCATE TABLE firm_phone;");
        $this->addSql("TRUNCATE TABLE firm_rubric_binde;");

        $this->addSql("TRUNCATE TABLE rubric;");
        $this->addSql("TRUNCATE TABLE building;");
    }
}
