<?php

namespace SkyengTT\APIBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

use SkyengTT\SkyengTTBundle\Entity\ErrorStat;

use \SkyengTT\SkyengTTBundle\Utils\AppLib AS Tool;

class AnswerController extends Controller
{
	
	/**
	 * В целях тестирования добавил шаблон простой формы добавления ответов на вопросы
     * @Route("/answer", name="skyeng_tt_answer", requirements={ "_method" : "GET" })
     * @Template()
    */
    public function indexAction()
    {
		return array();
	}
    /**
     * Добавление ответа на вопрос
     * @Route("/answer/add/{questionId}", name="skyeng_tt_answer_add", requirements={ "_method" : "POST" })
     * @Template()
     * @param int $question_id идентификатор вопроса, на который отправлен ответ
    */
    public function addAction($questionId)
    {
		$questionId = intval($questionId);
		if ($questionId) {
			$request = $this->container->get('request_stack')->getCurrentRequest();
			$anonymousAppUserId = $request->getSession()->get(Tool::ANONIMOUS_USER_ID);
			if (!$anonymousAppUserId) {
				return Tool::json404( array('info' => 'Lost session') );
			}
			$answerId = $request->get('answer_id');
			if (!$answerId) {
				return Tool::json404( array('info' => 'Empty answer!') );
			}
			$doctrine = $this->getDoctrine();
			$em = $doctrine->getEntityManager();
			$repository = $doctrine->getRepository('SkyengTTSkyengTTBundle:Vocabulary');
			$question = $repository->find($questionId);
			if (!$question) {
				return Tool::json404( array('info' => 'Question #' . $questionId . ' not found!') );
			}
			$answer = null;
			if ($question->getAnswerId() == $answerId) {
				$answer = $question;
			} else {
				$collection = $repository->findBy(array('answer_id' => $answerId));
				if ($collection) {
					$answer = current($collection);
				}
			}
			if (!$answer) {
				return Tool::json404( array('info' => 'Answer #' . $answerId . ' not found!') );
			}
			//В базе есть вопрос и ответ с такими номерами, значит можно записать результат
			if ($question->getAnswerId() == $answerId) {
				$usersRepository = $doctrine->getRepository('SkyengTTSkyengTTBundle:AnonymousUsers');
				$user = $usersRepository->find($anonymousAppUserId);
				$user->setScore( $user->getScore() + 1 );//TODO получать из конфига
				$em->persist($user);
				$em->flush();
				return Tool::json( array('success' => true, 'score' => $user->getScore(), 'next' => Tool::getNextQuestion($request, $doctrine))  );
			} else {
				$statisticRepository = $doctrine->getRepository('SkyengTTSkyengTTBundle:ErrorStat');
				$collection = $statisticRepository->findBy( array('quest_id' => $questionId, 'answer_id' => $answerId) );
				$statModel = null;
				if (!count($collection)) {
					$statModel = new ErrorStat();
					$statModel->setQuestId($questionId);
					$statModel->setAnswerId($answerId);
					$statModel->setQuantity(0);
				} else {
					$statModel = current($collection);
				}
				$statModel->setQuantity( $statModel->getQuantity() + 1);
				$em->persist($statModel);
				$em->flush();
				return Tool::json( array('success' => false) );
			}
		}
        return Tool::json404( array('info' => 'Question #' . $questionId . ' not found!') );
    }
}
