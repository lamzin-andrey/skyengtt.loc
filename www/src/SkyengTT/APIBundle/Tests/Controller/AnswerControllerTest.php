<?php

namespace SkyengTT\APIBundle\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

use SkyengTT\SkyengTTBundle\Entity\AnonymousUsers;

use SkyengTT\SkyengTTBundle\Entity\ErrorStat;

use SkyengTT\SkyengTTBundle\Entity\Vocabulary;

use \SkyengTT\SkyengTTBundle\Utils\TestLib AS U;

use \SkyengTT\SkyengTTBundle\Utils\AppTestLib AS ATL;

class AnswerController extends WebTestCase
{
	public function testaddAnswer()
	{
		/**
			Пытаемся ответить на вопрос неправильно.
			Пытаемся ответить на вопрос правильно.
			Отвечаем на вопрос неправильно еще дважды, убеждаемся что gameover

			Пытаемся ответить на несуществующий вопрос несуществующим ответом.
			Пытаемся ответить на несуществующий вопрос существующим ответом

			Пытаемся ответить на существующий вопрос несуществующим ответом.
			Пытаемся ответить на существующий вопрос существующим ответом.

			Создаем спец вопрос, отвечаем на него неправильно Random раз
			Убеждаемся, что в статистике будет ровно Random для этого вопроса
   
		 * */
	}
	
	
	
	
	
	
}

