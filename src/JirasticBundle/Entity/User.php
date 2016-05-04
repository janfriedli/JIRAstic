<?php
/**
 * User Entity
 */

namespace JirasticBundle\Entity;

use FOS\UserBundle\Model\User as BaseUser;
use Doctrine\ORM\Mapping as ORM;

/**
 * @package JirasticBundle\Entity
 * @author   Jan Friedli <jan.friedli@swisscom.com>
 * @license  https://opensource.org/licenses/GPL-3.0 Public License
 * @link     http://www.swisscom.ch
 *
 * Class DefaultController
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

    /**
     * User constructor.
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * @param string $jiraId Jira Id
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
     * @param string $jiraAccessToken Access token
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
