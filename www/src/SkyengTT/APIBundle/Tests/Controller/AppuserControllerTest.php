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
		$username = md5('angula_simfony_test_user'. time());
		$client = static::createClient();
		$this->_getEnvParam($client, $em, $repository, 'SkyengTT\SkyengTTBundle\Entity\AnonymousUsers');
		$crawler = $client->request('GET', '/appuser');
		
		
        $form = $crawler->selectButton('Send')->form(array(
            'username' => $username
        ));
        $client->submit($form);
        
        $response = $client->getResponse();
        $data = json_decode($response->getContent());
        $this->assertTrue((int)$data->id > 0);
        
        $list = $repository->findBy(array('name' => $username));
        $this->assertTrue(count($list) == 1);
        $user = $list[0];
        $this->assertTrue($user->getId() == $data->id);
        $this->_deleteUser($em, $data->id);
        
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

