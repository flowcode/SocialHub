<?php
namespace Flowcode\SocialHubBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\JoinColumn;
use Doctrine\ORM\Mapping\ManyToOne;

/**
 * UserSocialAccount

 * @ORM\Table(name="sh_user_social_account")
 * @ORM\Entity(repositoryClass="Flowcode\SocialHubBundle\Repository\UserSocialAccountRepository")
 */
class UserSocialAccount
{

    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @ManyToOne(targetEntity="Flowcode\UserBundle\Entity\UserInterface")
     * @JoinColumn(name="user_id", referencedColumnName="id")
     * */
    private $user;

    /**
     * @ManyToOne(targetEntity="Flowcode\SocialHubBundle\Entity\SocialNetwork")
     * @JoinColumn(name="social_network_id", referencedColumnName="id")
     * */
    private $socialNetwork;

    /**
     * @var string
     *
     * @ORM\Column(name="external_id", type="string", length=255, nullable=true)
     */
    private $externalId;

    /**
     * @var string
     *
     * @ORM\Column(name="access_token", type="string", length=255, nullable=true)
     */
    private $accessToken;

    /**
     * @var bool
     *
     * @ORM\Column(name="archived", type="boolean")
     */
    private $archived;

    public function __construct()
    {
        $this->archived = false;
    }

    /**
     * Get id
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set archived
     *
     * @param boolean $archived
     *
     * @return UserSocialAccount
     */
    public function setArchived($archived)
    {
        $this->archived = $archived;

        return $this;
    }

    /**
     * Get archived
     *
     * @return bool
     */
    public function getArchived()
    {
        return $this->archived;
    }

    /**
     * @return mixed
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * @param mixed $user
     */
    public function setUser($user)
    {
        $this->user = $user;
    }

    /**
     * @return mixed
     */
    public function getSocialNetwork()
    {
        return $this->socialNetwork;
    }

    /**
     * @param mixed $socialNetwork
     */
    public function setSocialNetwork($socialNetwork)
    {
        $this->socialNetwork = $socialNetwork;
    }

    /**
     * @return string
     */
    public function getAccessToken()
    {
        return $this->accessToken;
    }

    /**
     * @param string $accessToken
     */
    public function setAccessToken($accessToken)
    {
        $this->accessToken = $accessToken;
    }

    /**
     * @return string
     */
    public function getExternalId()
    {
        return $this->externalId;
    }

    /**
     * @param string $externalId
     */
    public function setExternalId($externalId)
    {
        $this->externalId = $externalId;
    }
}
