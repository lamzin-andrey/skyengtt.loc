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
	 * Проверка статистики учета неверных ответов
	*/
	public function testErrorStat()
	{
		$lim = 10; //у нас будет вот столько неправильных ответов 
		$questions = $this->_getExistsQuestions($lim);
		$this->assertTrue(count($questions) == $lim);
		
		//create new question
		$question = $this->_createQuestion();
		$id = $question->getId();
		
		$this->_prepareHtmlForm($id);
		
		//отвечаем на него неправильно Random раз
		ATL::createUser($userJson, $r, $name, $em, $client);
		
		$mostPopular = $questions[0]->getAnswerId();
		$n = rand(10,20);
		for ($i = 0; $i < $n; $i++) {
			$json = $this->_sendAnswer($mostPopular, $id, $client, $status, $html, 'SendAnyQuestion');
			$this->assertFalse($json->success);
		}
		//Убеждаемся, что в статистике будет ровно Random для этого вопроса
		U::getEnvParam($client, $em, $repository, 'SkyengTTSkyengTTBundle:ErrorStat');
		
		$stats = $repository->findBy(array('quest_id' => $id));
		$this->assertTrue(count($stats) == 1);
		
		$ctrl = 0;
		foreach ($stats as $errStat){
			$ctrl += $errStat->getQuantity();
		}
		
		$this->assertTrue($n == $ctrl);
		
		//снова отвечаем на него неправильно Random раз, но разными ответами
		for ($i = 0; $i < $n; $i++) {
			$answerId = $questions[ $i % $lim ]->getAnswerId();
			$this->_sendAnswer($answerId, $id, $client, $status, $html, 'SendAnyQuestion');
			$this->assertFalse($json->success);
		}
		
		//Убеждаемся, что в статистике будет ровно 2*Random для этого вопроса
		$stats = $repository->findBy(array('quest_id' => $id));
		$ctrl = 0;
		foreach ($stats as $errStat){
			$ctrl += $errStat->getQuantity();
		}
		$this->assertTrue( (2*$n) == $ctrl );
		//Убеждаемся, что самый популярный неправильный ответ действительно самый популярный
		$max = 0;
		$realMostPopular = 0;
		foreach ($stats as $errStat) {
			$q = $errStat->getQuantity();
			if ($q > $max) {
				$max = $q;
				$realMostPopular = $errStat->getAnswerId();
			}
		}
		$this->assertTrue($realMostPopular == $mostPopular);
		
		//удаляем тестовую статистику
		$em->createQuery("DELETE FROM SkyengTT\SkyengTTBundle\Entity\ErrorStat AS u WHERE u.quest_id = {$id}")
        ->getSingleResult();
        
        //удаляем тестовый вопрос
        $em->createQuery("DELETE FROM SkyengTT\SkyengTTBundle\Entity\Vocabulary AS u WHERE u.id = {$id}")
        ->getSingleResult();
        
		//удаляем тестового пользователя
		ATL::deleteUser($em, $userJson->id);
		
	}
	
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
		//Пробуем ответить на существующий вопрос существующим правильным ответом
		$questions = $this->_getExistsQuestions();
		$this->assertTrue(count($questions) > 1);
		$question = $questions[0];
		
        $json = $this->_sendAnswer($question->getAnswerId(), $question->getId(), $client);
        $this->assertTrue($json->success);
        $this->assertTrue($json->score > 0);
        ATL::assertNextQuestionIsPresent($json, $this);
        ATL::deleteUser($em, $userJson->id);
	}
	/**
	 * Отвечаем на вопрос неправильно трижды, убеждаемся что gameover
	*/
	public function testGameOver()
	{
		ATL::createUser($userJson, $r, $name, $em, $client);
		//Пробуем ответить на существующий вопрос существующим неправильным ответом
		$questions = $this->_getExistsQuestions();
		$this->assertTrue(count($questions) > 1);
		$question = $questions[0];
		$answer = $questions[1];
		
        $json = $this->_sendAnswer($answer->getAnswerId(), $question->getId(), $client);
        $json = $this->_sendAnswer($answer->getAnswerId(), $question->getId(), $client);
        $json = $this->_sendAnswer($answer->getAnswerId(), $question->getId(), $client);
        $this->assertFalse($json->success);
        $this->assertTrue( $json->gameover );
        ATL::deleteUser($em, $userJson->id);
	}
	/**
	 * Отвечаем на несуществующий вопрос несуществующим ответом.
	*/
	public function testNoExistsQA()
	{
		ATL::createUser($userJson, $r, $name, $em, $client);
        $json = $this->_sendAnswer(1, -1, $client, $status);
        $this->assertTrue($status == 404);
        $this->assertTrue($json->info == 'Answer #1 not found!');
        ATL::deleteUser($em, $userJson->id);
	}
	/**
	 * Пытаемся ответить на несуществующий вопрос существующим ответом
	*/
	public function testNoExistsQuestion()
	{
		ATL::createUser($userJson, $r, $name, $em, $client);
		$questions = $this->_getExistsQuestions();
		$this->assertTrue(count($questions) > 1);
		$answer = $questions[1];
		
        U::get('/answer', $client, $crawler, $html, $json);
		$form = $crawler->selectButton('SendNoExistsQuestion')->form(array(
            'answer_id' => $answer->getAnswerId(),
            'quest_id'  => -1
        ));
        
        $client->submit($form);
        $response = $client->getResponse();
        $text = $response->getContent();
        $status = $response->getStatusCode();
        $json = json_decode($text);
        
        $this->assertTrue( $status == 404 );
        $this->assertTrue( $json->info == 'Question #-1 not found!' );
        ATL::deleteUser($em, $userJson->id);
	}
	/**
	 * Отвечаем на существующий вопрос несуществующим ответом.
	*/
	public function testNoExistsAnswer()
	{
		ATL::createUser($userJson, $r, $name, $em, $client);
		
		$questions = $this->_getExistsQuestions();
		$this->assertTrue(count($questions) > 1);
		$question = $questions[0];
        $json = $this->_sendAnswer(1, $question->getId(), $client, $status);
        $this->assertTrue($status == 404);
        $this->assertTrue($json->info == 'Answer #1 not found!');
        ATL::deleteUser($em, $userJson->id);
	}
	
	private function _prepareHtmlForm($id)
    {
		$twigFile = dirname(__FILE__) . '/../../Resources/views/Answer/index.html.twig';
		if (!file_exists($twigFile)) {
			throw new Exception('Twig file not found!');
		}
		$aFileContent = array();
		$ready = false;
		$h = fopen($twigFile, 'r');
		while (!feof($h)) {
			$s = fgets($h);
			if (strpos($s, '<!--#QID-->') !== false) {
				$ready = true;
				$aFileContent[] = $s;
				continue;
			}
			if ($ready) {
				$buf = explode('<', $s);
				$s = $buf[0] . '<form action="/answer/add/'. $id .'" method="POST">' . "\n";
				$ready = false;
			}
			$aFileContent[] = $s;
		}
		fclose($h);
		$success = file_put_contents($twigFile, join('', $aFileContent));
		$this->assertTrue($success !== false);
	}
	private function _createQuestion()
    {
		$word = md5('angula_simfony_test_user'. time());
		$client = static::createClient();
		U::getEnvParam($client, $em, $repository, 'SkyengTT\SkyengTTBundle\Entity\Vocabulary');
		
		$question = new Vocabulary();
		$question->setEngWord($word);
		$question->setRusWord($word);
		$question->setAnswerId(crc32( $word . $word ));
		
		$em->persist($question);
		$em->flush();
		return $question;
	}
	
	private function _sendAnswer($answerId, $questionId, $client, &$status = null, &$text = null, $buttonText = 'Send')
	{
		//print "\n\n_sendAnswer: $questionId = questionId\n\n";
		
		U::get('/answer', $client, $crawler, $html, $json);
		$form = $crawler->selectButton($buttonText)->form(array(
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
        $status = $response->getStatusCode();
        $json = json_decode($text);
        return $json;
	}
	
	private function _getExistsQuestions($lim = 10)
	{
		$client = static::createClient();
		U::getEnvParam($client, $em, $r, 'SkyengTT\SkyengTTBundle\Entity\Vocabulary');
		return $r->findBy([], ['id' => 'ASC'], $lim, 0);
	}
}

