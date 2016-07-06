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

    public function getSocialNetwork($type)
    {
        $socialNetwork = $this->socialNetworkRepository->findOneBy(array(
            'type' => $type,
        ));

        return $socialNetwork;
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
        $provider_prefix = "socialhub_provider_";
        foreach ($socialNetworks as $socialNetwork) {
            $socialProvider = $this->container->get($provider_prefix . $socialNetwork->getType());

            $loginUrl = $socialProvider->getLoginUrl();

            $urls[] = array(
                'name' => $socialNetwork->getName(),
                'type' => $socialNetwork->getType(),
                'label' => "label." . $socialNetwork->getType(),
                'url' => $loginUrl,
            );
        }


        return $urls;
    }

}