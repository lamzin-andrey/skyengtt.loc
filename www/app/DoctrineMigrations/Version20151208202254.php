<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20151208202254 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        $data = '{
			"apple": "яблоко",
			"pear": "персик",
			"orange": "апельсин",
			"grape": "виноград",
			"lemon": "лимон",
			"pineapple": "ананас",
			"watermelon": "арбуз",
			"coconut": "кокос",
			"banana": "банан",
			"pomelo": "помело",
			"strawberry": "клубника",
			"raspberry": "малина",
			"melon": "дыня",
			"apricot": "абрикос",
			"mango": "манго",
			"pear": "слива",
			"pomegranate": "гранат",
			"cherry": "вишня"
		}';
		$data = json_decode($data, true);
		
		foreach ($data as $eng => $rus) {
			$eng = trim($eng);
			$rus = trim($rus);
			$crc = crc32($eng . $rus);
			$this->addSql("INSERT INTO vocabulary
				(eng_word, rus_word, answer_id) VALUES
				('{$eng}', '{$rus}', {$crc})");
		}
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs

    }
}
