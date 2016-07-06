<?php

namespace Flowcode\SocialHubBundle\Controller;

use Amulen\UserBundle\Entity\User;
use Flowcode\SocialHubBundle\Entity\UserSocialAccount;
use Flowcode\SocialHubBundle\Model\FacebookSocialProvider;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\Cookie;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;
use Symfony\Component\Security\Http\Event\InteractiveLoginEvent;
use Symfony\Component\Security\Http\SecurityEvents;

class ConnectorController extends Controller
{
    /**
     * @Route("/socialhub/connector", name="socialhub_connector")
     * @Template()
     */
    public function indexAction(Request $request)
    {

        $social_provider_id = $request->get('type');
        $provider_prefix = "socialhub_provider_";
        $socialProvider = $this->get($provider_prefix . $social_provider_id);

        $profile = $socialProvider->getUserProfile();

        $em = $this->getDoctrine()->getManager();
        $userSocialAccount = $em->getRepository('FlowcodeSocialHubBundle:UserSocialAccount')->findOneBy(array(
            'externalId' => $profile['id'],
        ));
        if ($profile) {

            if (!$userSocialAccount) {

                $accessToken = $socialProvider->getAccessToken();

                /* get social network */
                $socialNetwork = $em->getRepository('FlowcodeSocialHubBundle:SocialNetwork')->findOneBy(array(
                    'type' => $social_provider_id,
                ));

                if (!$socialNetwork) {
                    die('No social network');
                }

                $userManager = $this->container->get('flowcode.user');
                $user = $userManager->loadUserByUsername($profile['email']);

                if (!$user) {
                    $user = new User();
                    $user->setUsername($profile['email']);
                    $user->setEmail($profile['email']);
                    $user->setFirstname($profile['firstname']);
                    $user->setLastname($profile['lastname']);
                    $user->setPlainPassword($profile['id']);
                    $user->setStatus(User::STATUS_ACTIVE);

                    $userManager->create($user);
                }

                $userSocialAccount = new UserSocialAccount();
                $userSocialAccount->setUser($user);
                $userSocialAccount->setSocialNetwork($socialNetwork);
                $userSocialAccount->setExternalId($profile['id']);
                $userSocialAccount->setAccessToken($accessToken);
                $em->persist($userSocialAccount);
                $em->flush();

                $this->authenticateUser($request, $user);

            } else {

                $this->authenticateUser($request, $userSocialAccount->getUser());
            }

        }

        return $this->redirect($this->generateUrl('homepage'));
    }


    private function authenticateUser($request, User $user)
    {
        $session = $this->container->get('session');

        // FIXME: Configure main firewall.
        $firewall = $this->getParameter("socialhub_firewall");

        $token = new UsernamePasswordToken($user, null, $firewall, array('ROLE_USER'));
        $session->set('_security_' . $firewall, serialize($token));
        $session->save();

        $this->get('security.token_storage')->setToken($token);

        $this->container->get('event_dispatcher')->dispatch(
            SecurityEvents::INTERACTIVE_LOGIN,
            new InteractiveLoginEvent($request, $token)
        );
    }
}
