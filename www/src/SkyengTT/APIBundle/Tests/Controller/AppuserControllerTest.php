<?php

namespace SkyengTT\APIBundle\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

use SkyengTT\SkyengTTBundle\Entity\AnonymousUsers;

use \SkyengTT\SkyengTTBundle\Utils\TestLib AS U;

use \SkyengTT\SkyengTTBundle\Utils\AppTestLib AS ATL;

class AppuserController extends WebTestCase
{
	public function testCreateAction()
	{
		ATL::createUser($jsonResponseData, $repository, $username, $em);
        $data = $jsonResponseData;
        $this->assertTrue((int)$data->id > 0);
        $list = $repository->findBy(array('name' => $username));
        $this->assertTrue(count($list) == 1);
        $user = $list[0];
        $this->assertTrue($user->getId() == $data->id);
        ATL::deleteUser($em, $data->id);
	}
	
	public function testCreateSession()
	{
		//Пусть сразу вошли на /#/game  и пытаемся отправить ответ
		//(для простоты тестирования есть тестовая форма /answer)
		U::get('/answer', $client, $crawler, $html, $json);
		$form = $crawler->selectButton('Send')->form(array(
            'answer_id' => 4,
            'quest_id'  => 5
        ));
        $client->submit($form);
        $response = $client->getResponse();
        $jsonResponseData = json_decode($response->getContent());
        $this->assertTrue($jsonResponseData->info == 'Lost session');
        
        //А теперь сначала создадим пользователя
		ATL::createUser($userJson, $repository, $username, $em, $client);
		
		//Пробуем ответить на вопрос
		U::get('/answer', $client, $crawler, $html, $json);
		$form = $crawler->selectButton('Send')->form(array(
            'answer_id' => 4,
            'quest_id'  => 5
        ));
        $client->submit($form);
        $response = $client->getResponse();
        $jsonResponseData = json_decode($response->getContent());
        $this->assertTrue(@$jsonResponseData->info != 'Lost session');
        $this->assertTrue(@$jsonResponseData->info == 'Answer #4 not found!');
        ATL::deleteUser($em, $userJson->id);
	}
	
	
	
	
}

