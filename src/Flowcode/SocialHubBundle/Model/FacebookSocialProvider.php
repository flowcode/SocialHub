<?php

namespace Flowcode\SocialHubBundle\Model;


use Facebook\Exceptions\FacebookResponseException;
use Facebook\Exceptions\FacebookSDKException;
use Facebook\Facebook;

class FacebookSocialProvider implements SocialProvider
{

    const PROVIDER_NAME = 'socialhub_provider_facebook';

    private $config = null;
    private $facebookApp = null;

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

        $loginUrl = $helper->getLoginUrl('http://localhost:8000/socialhub/connector?type=socialhub_provider_facebook', $permissions);
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
     * @return array|null
     */
    public function getConfig()
    {
        if (is_null($this->config)) {

            $this->config = array(
                'app_id' => '616150438527128',
                'app_secret' => '8125281d5e4b4aed9e8c891f8cd22b13',
                'default_graph_version' => 'v2.6',
                'persistent_data_handler' => 'session',
            );
        }
        return $this->config;
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