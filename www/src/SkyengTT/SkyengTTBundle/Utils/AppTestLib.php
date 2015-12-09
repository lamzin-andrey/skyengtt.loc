<?php
/**
 * Библиотека для упрощения функционального тестирования конкретно этого приложения
*/
namespace SkyengTT\SkyengTTBundle\Utils;
use SkyengTT\SkyengTTBundle\Utils\TestLib;

class AppTestLib extends TestLib
{
	static public function createUser(&$jsonResponseData, &$repository, &$username, &$em, &$client = null)
    {
		$username = md5('angula_simfony_test_user'. time());
		$client = static::createClient();
		self::getEnvParam($client, $em, $repository, 'SkyengTT\SkyengTTBundle\Entity\AnonymousUsers');
		$crawler = $client->request('GET', '/appuser');
		
        $form = $crawler->selectButton('Send')->form(array(
            'username' => $username
        ));
        $client->submit($form);
        
        $response = $client->getResponse();
        $jsonResponseData = json_decode($response->getContent());
	}
	
	static public function deleteUser($em, $id)
    {
		$em->createQuery("DELETE FROM SkyengTT\SkyengTTBundle\Entity\AnonymousUsers AS u WHERE u.id = {$id}")
        ->getSingleResult();
	}
}
