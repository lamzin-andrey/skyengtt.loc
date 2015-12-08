<?php

namespace SkyengTT\APIBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

use SkyengTT\SkyengTTBundle\Entity\AnonymousUsers;

use \SkyengTT\SkyengTTBundle\Utils\AppLib AS Tool;

class QuestionController extends Controller
{
	
    /**
     * @Route("/question/random", name="skyeng_tt_question_random", requirements={ "_method" : "GET" })
     * @Template()
    */
    public function randomAction()
    {
		$request = $this->container->get('request_stack')->getCurrentRequest();
		$userId = $request->getSession()->get(Tool::ANONIMOUS_USER_ID);
		
		if ($userId) {
			$doctrine = $this->getDoctrine();
			$usersRepository = $doctrine->getRepository('SkyengTTSkyengTTBundle:AnonymousUsers');
			$user = $usersRepository->find($userId);
			if ($user) {
				return Tool::json( array('success' => true, 'score' => $user->getScore(), 'next' => Tool::getNextQuestion($request, $doctrine))  );
			}
		}
        return Tool::json404( array('info' => 'Empty username') );
    }
}
