<?php

namespace Flowcode\SocialHubBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

class DefaultController extends Controller
{
    /**
     * @Route("/test/socialhub")
     * @Template()
     */
    public function indexAction()
    {
        return array();
    }
}
