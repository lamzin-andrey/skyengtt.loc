<?php

namespace SkyengTT\APIBundle\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

use SkyengTT\SkyengTTBundle\Entity\AnonymousUsers;

use SkyengTT\SkyengTTBundle\Entity\ErrorStat;

use SkyengTT\SkyengTTBundle\Entity\Vocabulary;

use \SkyengTT\SkyengTTBundle\Utils\TestLib AS U;

use \SkyengTT\SkyengTTBundle\Utils\AppTestLib AS ATL;

use Symfony\Component\DomCrawler\Form AS CForm;

class AnswerController extends WebTestCase
{
	/**
			Отвечаем на вопрос неправильно еще дважды, убеждаемся что gameover

			Пытаемся ответить на несуществующий вопрос несуществующим ответом.
			Пытаемся ответить на несуществующий вопрос существующим ответом

			Пытаемся ответить на существующий вопрос несуществующим ответом.
			Пытаемся ответить на существующий вопрос существующим ответом.

			Создаем спец вопрос, отвечаем на него неправильно Random раз
			Убеждаемся, что в статистике будет ровно Random для этого вопроса
   
	*/
	/**
	 * Пытаемся ответить на вопрос неправильно.
	 * (Пробуем ответить на существующий вопрос существующим неправильным ответом)
	*/
	public function testaddWrongAnswer()
	{
		ATL::createUser($userJson, $r, $name, $em, $client);
		//Пробуем ответить на существующий вопрос существующим неправильным ответом
		$questions = $this->_getExistsQuestions();
		$this->assertTrue(count($questions) > 1);
		$question = $questions[0];
		$questionForAnswer = $questions[1];
		
        $json = $this->_sendAnswer($questionForAnswer->getAnswerId(), $question->getId(), $client);
        $this->assertFalse($json->success);
        
        ATL::deleteUser($em, $userJson->id);
	}
	/**
	 * Пытаемся ответить на вопрос правильно.
	*/
	public function testaddRightAnswer()
	{
		ATL::createUser($userJson, $r, $name, $em, $client);
		//Пробуем ответить на существующий вопрос существующим неправильным ответом
		$questions = $this->_getExistsQuestions();
		$this->assertTrue(count($questions) > 1);
		$question = $questions[0];
		
        $json = $this->_sendAnswer($question->getAnswerId(), $question->getId(), $client);
        $this->assertTrue($json->success);
        $this->assertTrue($json->score > 0);
        ATL::assertNextQuestionIsPresent($json, $this);
        ATL::deleteUser($em, $userJson->id);
	}
	
	private function _sendAnswer($answerId, $questionId, $client, &$text = null)
	{
		U::get('/answer', $client, $crawler, $html, $json);
		$form = $crawler->selectButton('Send')->form(array(
            'answer_id' => strval($answerId),
            'quest_id'  => $questionId
        ));
        
        /*
         * Попытка добратья до приватного свойства формы, по сути успешная, но сессия теряется...
         * $reflectionClass = new \ReflectionClass('Symfony\Component\DomCrawler\Form');
		$prop = $reflectionClass->getProperty('currentUri');
		$prop->setAccessible(true);
		$prop->setValue($form, 'http://ang.loc:8000/answer/add/' . $questionId);*/
		
        $client->submit($form);
        $response = $client->getResponse();
        $text = $response->getContent();
        $json = json_decode($text);
        return $json;
	}
	
	private function _getExistsQuestions()
	{
		$client = static::createClient();
		U::getEnvParam($client, $em, $r, 'SkyengTT\SkyengTTBundle\Entity\Vocabulary');
		return $r->findBy([], ['id' => 'ASC'], 10, 0);
	}
}

