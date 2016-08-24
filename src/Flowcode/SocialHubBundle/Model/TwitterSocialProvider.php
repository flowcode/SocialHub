<?php

namespace Flowcode\SocialHubBundle\Model;


use Abraham\TwitterOAuth\TwitterOAuth;
use Facebook\Exceptions\FacebookResponseException;
use Facebook\Exceptions\FacebookSDKException;
use Facebook\Facebook;
use Flowcode\SocialHubBundle\Entity\SocialNetwork;
use Flowcode\SocialHubBundle\Model\Exception\SocialProviderNotInitializedException;
use Symfony\Component\DependencyInjection\ContainerInterface;

class TwitterSocialProvider implements SocialProvider
{

    const PROVIDER_TYPE = 'twitter';

    private $config;
    /**
     * @var TwitterOAuth
     */
    private $connection;

    /**
     * @var string
     */
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
        $request_token = $this->getConnection()->oauth('oauth/request_token', array(
            'oauth_callback' => $this->container->getParameter("socialhub_host_url") . '/socialhub/connector?type=' . self::PROVIDER_TYPE
        ));

        $url = $this->getConnection()->url('oauth/authorize', array(
            'oauth_token' => $request_token['oauth_token'],
        ));

        return $url;
    }

    /**
     * Get the profile of current user.
     */
    public function getUserProfile($params = array())
    {
        $access_token = $this->getConnection()->oauth("oauth/access_token", array(
            "oauth_token" => $_GET['oauth_token'],
            "oauth_verifier" => $_GET['oauth_verifier']
        ));

        $this->getConnection()->setOauthToken($access_token['oauth_token'], $access_token['oauth_token_secret']);

        $response = $this->getConnection()->get("account/verify_credentials", array(
            "skip_status" => true,
            "include_email" => "true",
        ));

        return array(
            'id' => $response->id,
            'email' => (isset($response->email)) ? $response->email : $response->screen_name,
            'firstname' => null,
            'lastname' => null,
        );
    }

    /**
     * Get the token access.
     * @return OAuthToken tokenAbstraction.
     */
    public function getAccessToken($params = array())
    {
        $accessToken = $_GET['oauth_token'];

        return $accessToken;
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
            );
        }
    }


    /**
     * Get the app sdk.
     * @return TwitterOAuth isntance.
     */
    public function getConnection()
    {
        if (is_null($this->connection)) {
            $params = $this->getConfig();

            $this->connection = new TwitterOAuth(
                $params['app_id'],
                $params['app_secret']
            );
        }
        return $this->connection;
    }

}