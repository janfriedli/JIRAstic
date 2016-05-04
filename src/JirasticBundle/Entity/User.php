<?php
// src/AppBundle/Entity/User.php

namespace JirasticBundle\Entity;

use FOS\UserBundle\Model\User as BaseUser;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="fos_user")
 */
class User extends BaseUser
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @ORM\Column(name="jiraId", type="string", length=255, nullable=true)
     */
    private $jiraId;

    /**
     * @ORM\Column(name="jiraAccessToken", type="string", length=255, nullable=true)
     */
    private $jiraAccessToken;

    public function __construct()
    {
        parent::__construct();
        // your own logic
    }

    /**
     * @param string $jiraId
     * @return User
     */
    public function setJiraId($jiraId)
    {
        $this->jiraId = $jiraId;

        return $this;
    }

    /**
     * @return string
     */
    public function getJiraId()
    {
        return $this->jiraId;
    }

    /**
     * @param string $jiraAccessToken
     * @return User
     */
    public function setJiraAccessToken($jiraAccessToken)
    {
        $this->jiraAccessToken = $jiraAccessToken;

        return $this;
    }

    /**
     * @return string
     */
    public function getJiraAccessToken()
    {
        return $this->jiraAccessToken;
    }
}