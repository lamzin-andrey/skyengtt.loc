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
	
	static public function assertNextQuestionIsPresent($json, $_this)
	{
		$_this->assertTrue( isset($json->next) );
        $_this->assertTrue( isset($json->next->answers) );
        $_this->assertTrue( isset($json->next->question) );
        $_this->assertTrue( isset($json->next->question->id) );
        $_this->assertTrue( isset($json->next->question->word) );
        $_this->assertTrue( is_array($json->next->answers) );
        //Убеждаемся, что все ответы имеют все необходимые поля
        //и что среди них есть ответ на вопрос
        $rightAnswerId = self::_getRightAnswerId($json->next->question->id);
        $trueVariantPresent = false;
        foreach ($json->next->answers as $answer) {
			$_this->assertTrue( isset($answer->id) );
			$_this->assertTrue( isset($answer->word) );
			if ($answer->id == $rightAnswerId) {
				$rightAnswerPresent = true;
			}
		}
		$_this->assertTrue($rightAnswerPresent);
	}
	
	static private function _getRightAnswerId($questionId)
	{
		$client = static::createClient();
		self::getEnvParam($client, $em, $r, 'SkyengTT\SkyengTTBundle\Entity\Vocabulary');
		$question = $r->find($questionId);
		return $question->getAnswerId();
	}
}
