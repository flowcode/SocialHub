<?php
namespace Flowcode\SocialHubBundle\Service;

use Facebook\Facebook;
use Symfony\Component\HttpFoundation\Session\Session;
use Flowcode\SocialHubBundle\Entity\SocialNetwork;
use Doctrine\ORM\EntityManager;
use Symfony\Component\DependencyInjection\ContainerInterface;

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
    public function __construct(EntityManager $em, ContainerInterface $container)
    {
        $this->em = $em;
        $this->container = $container;
        $this->socialNetworkRepository = $this->em->getRepository(SocialNetwork::class);
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
