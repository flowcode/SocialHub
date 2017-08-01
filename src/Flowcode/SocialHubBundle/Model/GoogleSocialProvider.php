<?php
namespace Flowcode\SocialHubBundle\Model;

use Flowcode\SocialHubBundle\Entity\SocialNetwork;
use Flowcode\SocialHubBundle\Model\Exception\SocialProviderNotInitializedException;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Flowcode\SocialHubBundle\Model\OAuthToken;

class GoogleSocialProvider implements SocialProvider
{

    const PROVIDER_TYPE = 'google';

    private $config;
    private $connection;
    private $baseurl;
    private $container;

    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
    }

    public function isValidToken(OAuthToken $oAuthToken)
    {
        $tokenNumber = $oAuthToken->getToken();

        $request = 'https://www.googleapis.com/oauth2/v1/tokeninfo?access_token=' . $tokenNumber;
        $opts = array(
            'http' => array(
                'method' => "GET",
                'header' => "Accept-language: en\r\n" .
                "Cookie: foo=bar\r\n"
            )
        );
        try {
            $context = stream_context_create($opts);
            file_get_contents($request, false, $context);
        } catch (Exception $e) {
            return false;
        }
        return true;
    }

    /**
     * Get the auth url.
     * @return string url-
     */
    public function getLoginUrl($params)
    {
// TODO: Implement getLoginUrl() method.
    }

    /**
     * Get the profile of current user.
     */
    public function getUserProfile($params)
    {
        $accessToken = "";
        if (isset($params['access_token'])) {
            $accessToken = $params['access_token'];
        } else {
            $accessToken = $this->getAccessToken();
        }
        $request = 'https://www.googleapis.com/oauth2/v1/tokeninfo?access_token=' . $accessToken;
        $opts = array(
            'http' => array(
                'method' => "GET",
                'header' => "Accept-language: en\r\n" .
                "Cookie: foo=bar\r\n"
            )
        );
        try {
            $context = stream_context_create($opts);
            $content = json_decode(file_get_contents($request, false, $context), true);
        } catch (Exception $e) {
            return array();
        }
        return array(
            'id' => $content['user_id'],
            'email' => $content['email'],
        );
    }

    /**
     * Get the token access.
     * @return OAuthToken tokenAbstraction.
     */
    public function getAccessToken($params)
    {
// TODO: Implement getAccessToken() method.
    }

    /**
     * @param \App\UserBundle\Entity\SocialNetwork $socialNetwork
     * @return mixed
     */
    public function setupConfig(SocialNetwork $socialNetwork)
    {
// TODO: Implement setupConfig() method.
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
}
