<?php
namespace Flowcode\SocialHubBundle\Model;

/**
 * Description of SocialNetworkUserProfile
 *
 * @author juliansci
 */
class SocialNetworkUserProfile
{

    private $id;
    private $email;
    private $firstname;
    private $lastname;
    private $picture;

    public function getId()
    {
        return $this->id;
    }

    public function getEmail()
    {
        return $this->email;
    }

    public function getFirstname()
    {
        return $this->firstname;
    }

    public function getLastname()
    {
        return $this->lastname;
    }

    public function getPicture()
    {
        return $this->picture;
    }

    public function setId($id)
    {
        $this->id = $id;
    }

    public function setEmail($email)
    {
        $this->email = $email;
    }

    public function setFirstname($firstname)
    {
        $this->firstname = $firstname;
    }

    public function setLastname($lastname)
    {
        $this->lastname = $lastname;
    }

    public function setPicture($picture)
    {
        $this->picture = $picture;
    }
}
