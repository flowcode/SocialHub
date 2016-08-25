<?php

namespace Flowcode\SocialHubBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

class WidgetController extends Controller
{
    /**
     * Social options, about configured.
     * @Template()
     */
    public function loginOptionsAction($transDomain = null)
    {
        $socialNetworkService = $this->get('socialhub.service.socialnetwork');

        $networks = $socialNetworkService->getLoginUrls();

        return array(
            'networks' => $networks,
            'trans_domain' => $transDomain,
        );
    }
}
