<?php

namespace Flowcode\SocialHubBundle\Model;


interface SocialProvider
{

    /**
     * Check if it is valid or not.
     * @return boolean isvalid.
     */
    public function isValidToken(OAuthToken $oAuthToken);

    /**
     * Get the auth url.
     * @return string url-
     */
    public function getLoginUrl($params);

    /**
     * Get the profile of current user.
     */
    public function getUserProfile($params);

    /**
     * Get the token access.
     * @return OAuthToken tokenAbstraction.
     */
    public function getAccessToken($params);

    /**
     *
     */
    public function loginUser($params);

    /**
     * Call api to post content.
     */
    public function postContent($params);

}