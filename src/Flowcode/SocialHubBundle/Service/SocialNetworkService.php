<?php

namespace Flowcode\SocialHubBundle\Service;

use Facebook\Facebook;
use Flowcode\SocialHubBundle\Repository\SocialNetworkRepository;
use Symfony\Component\HttpFoundation\Session\Session;

/**
 * Class SocialNetworkService
 */
class SocialNetworkService
{

    /**
     * @var SocialNetworkRepository
     */
    private $socialNetworkRepository;
    private $container;

    /**
     * SocialNetworkService constructor.
     * @param $container
     * @param SocialNetworkRepository $socialNetworkRepository
     */
    public function __construct($container, SocialNetworkRepository $socialNetworkRepository)
    {
        $this->container = $container;
        $this->socialNetworkRepository = $socialNetworkRepository;
    }


    public function getLoginUrls()
    {

        $socialNetworks = $this->socialNetworkRepository->findBy(array(
            'enabled' => true,
            'loginEnabled' => true
        ));

        try {
            $session = new Session();
            if (!$session->isStarted()) {
                $session->start();
            }
        } catch (\Exception $e) {

        }

        $urls = array();
        foreach ($socialNetworks as $network) {

            $socialProvider = $this->container->get($network->getType());
            $loginUrl = $socialProvider->getLoginUrl();

            $urls[] = array(
                'name' => $network->getName(),
                'type' => "label." . $network->getType(),
                'url' => $loginUrl,
            );
        }


        return $urls;
    }

}