<?php
namespace Flowcode\SocialHubBundle\Service;

use Doctrine\ORM\EntityManager;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Flowcode\SocialHubBundle\Entity\UserSocialAccount;

class SocialProviderService
{

    protected $container;
    protected $em;

    public function __construct(EntityManager $em, ContainerInterface $container)
    {
        $this->em = $em;
        $this->container = $container;
    }

    public function getProvider($socialNetwork)
    {
        $provider = null;
        if ($socialNetwork == 'facebook') {
            $provider = $this->container->get('socialhub_provider_facebook');
        }
        if ($socialNetwork == 'google') {
            $provider = $this->container->get('socialhub_provider_google');
        }

        return $provider;
    }

    public function isValidToken($socialNetworkName, $OAuthToken)
    {
        $provider = $this->getProvider($socialNetworkName);
        if ($provider == null) {
            throw new \InvalidArgumentException('invalid:social:provider:name');
        }
        return $provider->isValidToken($OAuthToken);
    }
}
