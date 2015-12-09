<?php

namespace SkyengTT\SkyengTTBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

class DefaultController extends Controller
{
    /**
     * @Route("/")
     * @Template()
    */
    public function indexAction()
    {
        return array();
    }
    /**
     * @Route("/hello")
     * @Template("SkyengTTSkyengTTBundle:Default:hello.html.twig")
    */
    public function helloAction()
    {
        return array();
    }
    
    /**
     * @Route("/game")
     * @Template("SkyengTTSkyengTTBundle:Default:game.html.twig")
    */
    public function gameAction()
    {
        return array();
    }
    
    /**
     * @Route("/gameover")
     * @Template("SkyengTTSkyengTTBundle:Default:gameover.html.twig")
    */
    public function gameoverAction()
    {
        return array();
    }
    /**
     * @Route("/win")
     * @Template("SkyengTTSkyengTTBundle:Default:win.html.twig")
    */
    public function winAction()
    {
        return array();
    }
}
