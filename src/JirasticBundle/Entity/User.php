<?php
/**
 * User Entity
 */

namespace JirasticBundle\Entity;

use FOS\UserBundle\Model\User as BaseUser;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * @package JirasticBundle\Entity
 * @author   Jan Friedli <jan.friedli@swisscom.com>
 * @license  https://opensource.org/licenses/GPL-3.0 Public License
 * @link     http://www.swisscom.ch
 *
 * @ORM\Entity
 * @ORM\Table(name="fos_user")
 *
 * Class User
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
     * @ORM\OneToMany(targetEntity="Board", mappedBy="user")
     */
    private $boards;

    /**
     * @ORM\OneToMany(targetEntity="Customfield", mappedBy="user")
     */
    private $customfields;

    /**
     * User constructor.
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
        $this->boards = new ArrayCollection();
        $this->customfields = new ArrayCollection();
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

    /**
     * Add boards
     *
     * @param \JirasticBundle\Entity\Board $boards Boards
     * @return User
     */
    public function addBoard(\JirasticBundle\Entity\Board $boards)
    {
        $this->boards[] = $boards;

        return $this;
    }

    /**
     * Remove boards
     *
     * @param \JirasticBundle\Entity\Board $boards Boards
     * @return void
     */
    public function removeBoard(\JirasticBundle\Entity\Board $boards)
    {
        $this->boards->removeElement($boards);
    }

    /**
     * Get boards
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getBoards()
    {
        return $this->boards;
    }

    /**
     * Add customfields
     *
     * @param \JirasticBundle\Entity\Customfield $customfields Customfields
     * @return User
     */
    public function addCustomfield(\JirasticBundle\Entity\Customfield $customfields)
    {
        $this->customfields[] = $customfields;

        return $this;
    }

    /**
     * Remove customfields
     *
     * @param \JirasticBundle\Entity\Customfield $customfields Customfields
     * @return void
     */
    public function removeCustomfield(\JirasticBundle\Entity\Customfield $customfields)
    {
        $this->customfields->removeElement($customfields);
    }

    /**
     * Get customfields
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getCustomfields()
    {
        return $this->customfields;
    }
}
