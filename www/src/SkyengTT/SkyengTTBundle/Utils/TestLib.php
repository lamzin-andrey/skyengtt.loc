<?php
/**
 * Библиотека для упрощения функционального тестирования
*/
namespace SkyengTT\SkyengTTBundle\Utils;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class TestLib extends WebTestCase
{
	/**
	 * @param $client
	 * @param EntityManager &$em
	 * @param Repository &$r
	 * @param string $scope например SkyengTT\SkyengTTBundle\Entity\AnonymousUsers или иногда удается в форме StudyAppUserBundle:User
	*/
	static public function getEnvParam($client, &$em, &$r, $scope)
    {
        $doctrine = $client->getContainer()->get("doctrine");
        $em = $doctrine->getEntityManager();
        $r = $doctrine->getRepository($scope);
    }
    /**
	 * @param string $url например /answer
	 * @param &$client если не передан, то создается
	 * @param &$crawler создается
	 * @param string &$responseText заполняется
	 * @param StdClass &$jsonResponse пытается декодировать туда &$responseText
	*/
	static public function get($url, &$client = null, &$crawler, &$responseText, &$jsonResponse) {
		if (!$client) {
			$client = static::createClient();
		}
		$crawler = $client->request('GET', $url);
		$response = $client->getResponse();
		$responseText = $response->getContent();
		$jsonResponse = json_decode($responseText);
	}
}
