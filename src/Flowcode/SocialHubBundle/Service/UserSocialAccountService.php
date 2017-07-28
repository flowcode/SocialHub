<?php
namespace Flowcode\SocialHubBundle\Service;

use Doctrine\ORM\EntityManager;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Flowcode\SocialHubBundle\Entity\UserSocialAccount;

class UserSocialAccountService
{

    private $userSocialAccountRepository;
    protected $container;
    protected $em;

    public function __construct(EntityManager $em, ContainerInterface $container)
    {
        $this->em = $em;
        $this->container = $container;
        $this->userSocialAccountRepository = $this->em->getRepository(UserSocialAccount::class);
    }

    public function findByExternalId($externalId)
    {
        return $this->userSocialAccountRepository->findOneBy(array(
                'externalId' => $externalId,
        ));
    }

    public function getSocialNetworkProfile($socialNetwork, $accessToken)
    {
        $socialProviderService = $this->container->get('socialhub.service.socialprovider');
        $provider = $socialProviderService->getProvider($socialNetwork);
        return $provider->getUserProfile(array('access_token' => $accessToken));
    }

    public function create($userSocialAccount)
    {
        $this->em->persist($userSocialAccount);
        $this->em->flush();
    }
}
