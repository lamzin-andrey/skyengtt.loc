<?php
/**
 * Created by PhpStorm.
 * User: andrey
 * Date: 02.12.15
 * Time: 16:30
*/
namespace SkyengTT\SkyengTTBundle\Utils;
use Symfony\Component\HttpFoundation\Response;
use Doctrine\ORM\Query\ResultSetMapping;

class AppLib
{
	const CURRENT_QUESTION = 'CURRENT_QUESTION';
	const ANONIMOUS_USER_ID = 'anonymousAppUserId';
	/** словарь может быть большим, поэтому путьс пользователь ответит только на QUESTIONS_LIMIT вопросов*/
	const QUESTIONS_LIMIT = 10;
	/** в этом ключе сессии храним ответы, которые уже показывались пользователю */
	const LAST_QUESTIONS = 'LAST_QUESTIONS';
	/** в этом ключе сессии храним количество неверных ответов */
	const WRONG_ANSWER_COUNT = 'WRONG_ANSWER_COUNT';
	/** максимально допустимое количество неверных ответов */
	const WRONG_ANSWER_LIMIT = 3;
	
	
    /**
     * Обертка для быстрого добавлению к JSON ответу 404 статуса
     */
    static public function json404($data)
    {
        $data['message'] = '404 Not Found';
        $response = self::json($data);
        $response->setStatusCode(404);
        return $response;
    }
    /**
     * json response
     */
    static public function json($data)
    {
        $data = json_encode($data);
        $response = new Response($data);
        $response->headers->set("Content-Type", "application/json");
        return $response;
    }
    /**
     * Преобразует модель в массив
     * TODO remove me
    */
    static public function toArray($model)
    {
        $data = get_class_methods($model);
        //var_dump($data); die;
        $result = array();
        foreach ($data as $method) {
            if (strpos($method, 'get') === 0) {
                $field = self::toSnake($method);
                $result[$field] = $model->$method();
            }
        }
        return $result;
    }
    /**
     * Получает из имени get метода класса в camelCase имя свойства в snake_case
     * TODO remove me
    */
    static private function toSnake($s)
    {
        $q = strtoupper($s);
        $count = strlen($s);
        $words = array();
        $word = '';
        for ($i = 0; $i < $count; $i++){
            if ($q[$i] == $s[$i]) {
                if ($word != 'get') {
                    $words[] = strtolower($word);
                }
                $word = '';
            }
            $word .= $s[$i];
        }
        if ($word) {
            $words[] = strtolower($word);
        }
        $r = join('_', $words);
        return $r;
    }
    /**
     * Получает следующий вопрос, жаль что приходится в этом случае использовать нативный sql, но без него нормально не сделать
     * @param Request $request
     * @param Doctrine $doctrine
     * @return array | bool true если вопросы закончились
    */
    static public function getNextQuestion($request, $doctrine)
    {
		$session = $request->getSession();
		$n = $session->get(self::CURRENT_QUESTION);
		//если заданы все вопросы, вернуть true
		if ($n == self::QUESTIONS_LIMIT) {
			return true;
		}
		$result = array();
		$notIn = $session->get(self::LAST_QUESTIONS, array(0));
		$sNotIn = join(',', $notIn);
		
		
		$rsm = new ResultSetMapping();
		$rsm->addEntityResult('SkyengTT\SkyengTTBundle\Entity\Vocabulary', 'v');
		$rsm->addFieldResult('v', 'id', 'id');
		$rsm->addFieldResult('v', 'eng_word', 'eng_word');
		$rsm->addFieldResult('v', 'rus_word', 'rus_word');
		$rsm->addFieldResult('v', 'answer_id', 'answer_id');
		
		$questionResult = $doctrine->getEntityManager()->createNativeQuery(
            "SELECT v.id, v.eng_word, v.rus_word, v.answer_id FROM vocabulary AS v WHERE v.id NOT IN ({$sNotIn}) ORDER BY RANDOM() LIMIT 1", $rsm)
            ->getResult();
        $answerLangWord = 'getRusWord';
        $questionLangWord = 'getEngWord';
        if (rand(0, 1000) % 2 != 0) {
			$buf = $answerLangWord;
			$answerLangWord = $questionLangWord;
			$questionLangWord = $buf;
		}
        
        if ($questionResult) {
			$question = current($questionResult);
			$questionId = $question->getId();
			$notIn[$questionId] = $questionId;
			$session->set(self::LAST_QUESTIONS, $notIn);
			
			$answerResult = $doctrine->getEntityManager()->createNativeQuery(
            "SELECT v.id, v.eng_word, v.rus_word, v.answer_id FROM vocabulary AS v WHERE id != {$questionId} ORDER BY RANDOM() LIMIT 4", $rsm)
            ->getResult();
            $result['answers'] = array();
            $result['question'] = array('id' => $question->getId(), 'word' => $question->$questionLangWord());
            foreach ($answerResult as $item) {
				if ($item->getId() != $question->getId()) {
					$result['answers'][] = array( 'word' => $item->$answerLangWord(), 'id' => $item->getAnswerId());
				}
			}
			$k = rand(0, 3);
			$result['answers'][ $k ] = array( 'word' => $question->$answerLangWord(), 'id' => $question->getAnswerId());
		}
		$session->set(self::CURRENT_QUESTION, $n + 1);
		return $result;
	}
}
