<?php

namespace SkyengTT\APIBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

use SkyengTT\SkyengTTBundle\Entity\AnonymousUsers;

use \SkyengTT\SkyengTTBundle\Utils\AppLib AS Tool;

class AppuserController extends Controller
{
	
	/**
	 * В целях тестирования добавил шаблон простой формы добавления пользователя
     * @Route("/appuser", name="skyeng_tt_appuser", requirements={ "_method" : "GET" })
     * @Template()
    */
    public function indexAction()
    {
		return array();
	}
    /**
     * @Route("/appuser/create", name="skyeng_tt_appuser_create", requirements={ "_method" : "POST" })
     * @Template()
    */
    public function createAction()
    {
		$request = $this->container->get('request_stack')->getCurrentRequest();
		$username = $request->get('username');
		if ($username) {
			$doctrine = $this->getDoctrine();
			$em = $doctrine->getEntityManager();
			$user = new AnonymousUsers();
			$user->setName($username);
			$user->setTimestamp(time());
			$user->setScore(0);
			$em->persist($user);
			$em->flush();
			$request->getSession()->set(Tool::ANONIMOUS_USER_ID, $user->getId());
			$request->getSession()->set(Tool::CURRENT_QUESTION, 0);
			$request->getSession()->set(Tool::WRONG_ANSWER_COUNT, 0);
			$request->getSession()->set(Tool::LAST_QUESTIONS, array());
			return Tool::json( array('id' => $user->getId()) );
		}
        return Tool::json404( array('info' => 'empty username') );
    }
}
