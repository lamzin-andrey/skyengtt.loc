<?php

namespace SkyengTT\APIBundle\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

use SkyengTT\SkyengTTBundle\Entity\AnonymousUsers;

use \SkyengTT\SkyengTTBundle\Utils\AppLib AS Tool;

class AppuserController extends WebTestCase
{
	public function testCreateAction()
	{
		$this->_createUser($jsonResponseData, $repository, $username, $em);
        $data = $jsonResponseData;
        $this->assertTrue((int)$data->id > 0);
        $list = $repository->findBy(array('name' => $username));
        $this->assertTrue(count($list) == 1);
        $user = $list[0];
        $this->assertTrue($user->getId() == $data->id);
        $this->_deleteUser($em, $data->id);
	}
	
	public function testCreateSession()
	{
		//Пусть сразу вошли на /#/game  и пытаемся отправить ответ
		//(для простоты тестирования есть тестовая форма /answer)
		$this->_get('/answer', $client, $crawler, $html, $json);
		$form = $crawler->selectButton('Send')->form(array(
            'answer_id' => 4,
            'quest_id'  => 5
        ));
        $client->submit($form);
        $response = $client->getResponse();
        $jsonResponseData = json_decode($response->getContent());
        $this->assertTrue($jsonResponseData->info == 'Lost session');
        
        //А теперь сначала создадим пользователя
		$this->_createUser($userJson, $repository, $username, $em, $client);
		
		//Пробуем ответить на вопрос
		$this->_get('/answer', $client, $crawler, $html, $json);
		$form = $crawler->selectButton('Send')->form(array(
            'answer_id' => 4,
            'quest_id'  => 5
        ));
        $client->submit($form);
        $response = $client->getResponse();
        $jsonResponseData = json_decode($response->getContent());
        $this->assertTrue(@$jsonResponseData->info != 'Lost session');
        $this->assertTrue(@$jsonResponseData->info == 'Answer #4 not found!');
        $this->_deleteUser($em, $userJson->id);
	}
	
	
	private function _createUser(&$jsonResponseData, &$repository, &$username, &$em, &$client = null)
    {
		$username = md5('angula_simfony_test_user'. time());
		$client = static::createClient();
		$this->_getEnvParam($client, $em, $repository, 'SkyengTT\SkyengTTBundle\Entity\AnonymousUsers');
		$crawler = $client->request('GET', '/appuser');
		
		
        $form = $crawler->selectButton('Send')->form(array(
            'username' => $username
        ));
        $client->submit($form);
        
        $response = $client->getResponse();
        $jsonResponseData = json_decode($response->getContent());
	}
	
	private function _get($url, &$client = null, &$crawler, &$responseText, &$jsonResponse) {
		if (!$client) {
			$client = static::createClient();
		}
		$crawler = $client->request('GET', $url);
		$response = $client->getResponse();
		$responseText = $response->getContent();
		$jsonResponse = json_decode($responseText);
	}
	
	private function _deleteUser($em, $id)
    {
		$em->createQuery("DELETE FROM SkyengTT\SkyengTTBundle\Entity\AnonymousUsers AS u WHERE u.id = {$id}")
        ->getSingleResult();
	}
	
	private function _getEnvParam($client, &$em, &$r, $scope)
    {
        $doctrine = $client->getContainer()->get("doctrine");
        $em = $doctrine->getEntityManager();
        $r = $doctrine->getRepository($scope);
    }
}

