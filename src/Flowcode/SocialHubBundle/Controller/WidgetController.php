<?php

namespace Flowcode\SocialHubBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

class WidgetController extends Controller
{
    /**
     * @Template()
     */
    public function loginOptionsAction()
    {

        $socialNetworkService = $this->get('socialhub.service.socialnetwork');

        $networks = $socialNetworkService->getLoginUrls();

        return array(
            'networks' => $networks
        );
    }
}
