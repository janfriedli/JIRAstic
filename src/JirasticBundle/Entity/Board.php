<?php
/**
 * Board
 */

namespace JirasticBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @package JirasticBundle\Entity
 * @author   Jan Friedli <jan.friedli@swisscom.com>
 * @license  https://opensource.org/licenses/GPL-3.0 Public License
 * @link     http://www.swisscom.ch
 *
 * Class Board
 *
 * @ORM\Table(name="board")
 * @ORM\Entity(repositoryClass="JirasticBundle\Repository\BoardRepository")
 */
class Board
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
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=255, nullable=false, unique=false)
     * @Assert\NotBlank()
     * @Assert\Type(
     *     type="string",
     *     message="This value needs to be a string"
     * )
     */
    private $name;

    /**
     * @var int
     *
     * @ORM\Column(name="jira_id", type="integer", nullable=false, unique=false)
     * @Assert\NotBlank()
     * @Assert\Type(
     *     type="integer",
     *     message="This value needs to be an integer"
     * )
     */
    private $jiraId;

    /**
     * @ORM\ManyToMany(targetEntity="Status", inversedBy="board", cascade={"all"} )
     * @Assert\NotBlank()
     * @Assert\Valid()
     */
    protected $statuses;

    /**
     * @ORM\ManyToOne(targetEntity="User", inversedBy="boards")
     * @ORM\JoinColumn(name="user_id", referencedColumnName="id")
     */
    private $user;

    /**
     * @var int
     *
     * @ORM\Column(name="last_modified", type="datetime", nullable=true, unique=false)
     * @Assert\NotBlank()
     */
    private $lastModified;

    /**
     * Board constructor.
     */
    public function __construct()
    {
        $this->statuses = new ArrayCollection();
    }

    /**
     * @return \DateTime
     */
    public function getLastModified()
    {
        return $this->lastModified;
    }

    /**
     * @param \DateTime $lastModified last modified timestamp
     * @return void
     */
    public function setLastModified($lastModified)
    {
        $this->lastModified = $lastModified;
    }

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param string $name Name
     * @return void
     */
    public function setName($name)
    {
        $this->name = $name;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param string $jiraId Jira id
     * @return void
     */
    public function setJiraId($jiraId)
    {
        $this->jiraId = $jiraId;
    }

    /**
     * @return string
     */
    public function getJiraId()
    {
        return $this->jiraId;
    }

    /**
     * @param \JirasticBundle\Entity\Status $status Status
     * @return void
     */
    public function addStatus(\JirasticBundle\Entity\Status $status)
    {
        $this->statuses[] = $status;
    }

    /**
     * @param \JirasticBundle\Entity\Status $status Status
     * @return void
     */
    public function removeStatus(\JirasticBundle\Entity\Status $status)
    {
        $this->statuses->removeElement($status);
    }
    
    
    /**
     * @return void
     */
    public function removeStatuses()
    {
        foreach ($this->getStatuses() as $status) {
            $this->statuses->removeElement($status);
        }
    }

    /**
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getStatuses()
    {
        return $this->statuses;
    }

    /**
     * @return string representation of this class
     */
    public function __toString()
    {
        return $this->name;
    }

    /**
     * Set user
     *
     * @param \JirasticBundle\Entity\User $user User
     * @return Board
     */
    public function setUser(\JirasticBundle\Entity\User $user = null)
    {
        $this->user = $user;

        return $this;
    }

    /**
     * Get user
     *
     * @return \JirasticBundle\Entity\User
     */
    public function getUser()
    {
        return $this->user;
    }
}
