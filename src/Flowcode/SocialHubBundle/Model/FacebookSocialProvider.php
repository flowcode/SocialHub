<?php

namespace Flowcode\SocialHubBundle\Model;


use Facebook\Exceptions\FacebookResponseException;
use Facebook\Exceptions\FacebookSDKException;
use Facebook\Facebook;
use Flowcode\SocialHubBundle\Entity\SocialNetwork;
use Flowcode\SocialHubBundle\Model\Exception\SocialProviderNotInitializedException;
use Symfony\Component\DependencyInjection\ContainerInterface;

class FacebookSocialProvider implements SocialProvider
{

    const PROVIDER_TYPE = 'facebook';

    private $config;
    private $facebookApp;
    private $baseurl;

    /**
     * @var ContainerInterface
     */
    private $container;

    /**
     * FacebookSocialProvider constructor.
     * @param ContainerInterface $container
     */
    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
    }


    /**
     * Check if it is valid or not.
     * @return boolean isvalid.
     */
    public function isValidToken(OAuthToken $oAuthToken)
    {
        // TODO: Implement isValidToken() method.
    }

    /**
     * Get the auth url.
     * @return string url-
     */
    public function getLoginUrl($params = array())
    {
        $helper = $this->getFBApp()->getRedirectLoginHelper();

        //TODO: Config permissions.
        $permissions = ['email'];

        $loginUrl = $helper->getLoginUrl($this->container->getParameter("socialhub_host_url") . '/socialhub/connector?type=' . self::PROVIDER_TYPE, $permissions);
        return $loginUrl;
    }

    /**
     * Get the profile of current user.
     */
    public function getUserProfile($params = array())
    {
        $accessToken = "";
        if (isset($params['access_token'])) {
            $accessToken = $params['access_token'];
        } else {
            $accessToken = $this->getAccessToken();
        }

        //TODO: Config permissions.
        try {
            $response = $this->getFBApp()->get('/me?fields=name,email,first_name,last_name', $accessToken);
        } catch (FacebookResponseException $e) {

            return null;
        } catch (FacebookSDKException $e) {

            return null;
        }

        $me = $response->getGraphUser();
        return array(
            'id' => $me->getId(),
            'email' => $me->getEmail(),
            'firstname' => $me->getFirstName(),
            'lastname' => $me->getLastName(),
        );
    }

    /**
     * Get the token access.
     * @return OAuthToken tokenAbstraction.
     */
    public function getAccessToken($params = array())
    {
        $helper = $this->getFBApp()->getRedirectLoginHelper();

        try {
            $accessToken = $helper->getAccessToken();
        } catch (FacebookResponseException $e) {

            return null;
        } catch (FacebookSDKException $e) {

            return null;
        }

        return $accessToken->getValue();

    }

    /**
     *
     */
    public function loginUser($params)
    {
        // TODO: Implement loginUser() method.
    }

    /**
     * Call api to post content.
     */
    public function postContent($params)
    {
        // TODO: Implement postContent() method.
    }

    /**
     * @return mixed
     * @throws SocialProviderNotInitializedException
     */
    public function getConfig()
    {
        if (is_null($this->config)) {
            $socialNetworkService = $this->container->get('socialhub.service.socialnetwork');
            $socialNetwork = $socialNetworkService->getSocialNetwork(self::PROVIDER_TYPE);
            $this->setupConfig($socialNetwork);
        }
        return $this->config;
    }

    public function setupConfig(SocialNetwork $socialNetwork)
    {
        if (is_null($this->config)) {
            $this->config = array(
                'app_id' => $socialNetwork->getClientId(),
                'app_secret' => $socialNetwork->getClientSecret(),
                'default_graph_version' => 'v2.6',
                'persistent_data_handler' => 'session',
            );
        }
    }


    /**
     * Get the facebook sdk.
     * @return Facebook facebook Api isntance.
     */
    public function getFBApp()
    {
        if (is_null($this->facebookApp)) {
            $this->facebookApp = new Facebook($this->getConfig());
        }
        return $this->facebookApp;
    }

}