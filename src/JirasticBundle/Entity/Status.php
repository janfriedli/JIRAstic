<?php
/**
 * Status
 */

namespace JirasticBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @package JirasticBundle\Entity
 * @author   Jan Friedli <jan.friedli@swisscom.com>
 * @license  https://opensource.org/licenses/GPL-3.0 Public License
 * @link     http://www.swisscom.ch
 *
 * Class Status
 *
 * @ORM\Table(name="status")
 * @ORM\Entity(repositoryClass="JirasticBundle\Repository\StatusRepository")
 */
class Status
{
    /**
     * @var int $id Id
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string $title Title
     *
     * @ORM\Column(name="title", type="string", length=255, nullable=false)
     * @Assert\NotBlank()
     * @Assert\Type(
     *     type="string",
     *     message="This value needs to be a string"
     * )
     */
    private $title;

    /**
     * @var string $titleShort Shortened Title
     *
     * @ORM\Column(name="titleShort", type="string", length=255, nullable=false)
     * @Assert\NotBlank()
     * @Assert\Type(
     *     type="string",
     *     message="This value needs to be a string"
     * )
     */
    private $titleShort;

    /**
     * @var string $bgcolor Background Color (Hex)
     *
     * @ORM\Column(name="bgcolor", type="string", length=255,  nullable=false)
     * @Assert\NotBlank()
     * Hex Code Regex taken from Regexer.com Community matches: #xxxxxx
     * @Assert\Regex("/#{1}([\da-fA-F]{2})([\da-fA-F]{2})([\da-fA-F]{2})/")
     */
    private $bgcolor;

    /**
     * @var string $icon Icon (font-awesome class name)
     *
     * @ORM\Column(name="icon", type="string", length=30, nullable=false)
     * @Assert\NotBlank()
     * @Assert\Type(
     *     type="string",
     *     message="This value needs to be a string"
     * )
     */
    private $icon;

    /**
     * @var string $class Predefined Class name
     *
     * @ORM\Column(name="class", type="string", length=30, nullable=false)
     * @Assert\NotBlank()
     * @Assert\Type(
     *     type="string",
     *     message="This value needs to be a string"
     * )
     */
    private $class;

    /**
     * @var int $orderId Order id for the slides
     *
     * @ORM\Column(name="order_id", type="integer", nullable=false)
     * @Assert\NotBlank()
     * @Assert\Type(
     *     type="integer",
     *     message="This value needs to be an integer"
     * )
     */
    private $orderId;

    /**
     * @ORM\ManyToMany(targetEntity="Board", mappedBy="Status", cascade={"all"})
     * @Assert\Type(
     *     type="array",
     *     message="This value needs to be an array"
     * )
     */
    protected $board;

    /**
     * @ORM\ManyToMany(targetEntity="StatusMapping", inversedBy="status", cascade={"all"} )
     * @Assert\Type(
     *     type="array",
     *     message="This value needs to be an array"
     * )
     */
    private $statusMapping;

    /**
     * Status constructor.
     */
    public function __construct()
    {
        $this->statusMapping = new ArrayCollection();
    }

    /**
     * @return mixed
     */
    public function getStatusMapping()
    {
        return $this->statusMapping;
    }

    /**
     * @param mixed $statusMapping StatusMapping
     * @return void
     */
    public function setStatusMapping($statusMapping)
    {
        $this->statusMapping = $statusMapping;
    }

    /**
     * @return int
     */
    public function getOrderId()
    {
        return $this->orderId;
    }

    /**
     * @param int $orderId Order id
     * @return void
     */
    public function setOrderId($orderId)
    {
        $this->orderId = $orderId;
    }


    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param string $title Title
     * @return void
     */
    public function setTitle($title)
    {
        $this->title = $title;
    }

    /**
     * @return string
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * @param string $titleShort Short Title
     * @return void
     */
    public function setTitleShort($titleShort)
    {
        $this->titleShort = $titleShort;
    }

    /**
     * @return string
     */
    public function getTitleShort()
    {
        return $this->titleShort;
    }

    /**
     * @param string $bgcolor Background Color
     * @return void
     */
    public function setBgcolor($bgcolor)
    {
        $this->bgcolor = $bgcolor;
    }

    /**
     * @return string
     */
    public function getBgcolor()
    {
        return $this->bgcolor;
    }

    /**
     * @param string $icon Icon
     * @return void
     */
    public function setIcon($icon)
    {
        $this->icon = $icon;
    }

    /**
     * @return string
     */
    public function getIcon()
    {
        return $this->icon;
    }

    /**
     * @param string $class Class
     * @return void
     */
    public function setClass($class)
    {
        $this->class = $class;
    }

    /**
     * @return string
     */
    public function getClass()
    {
        return $this->class;
    }

    /**
     * @param \JirasticBundle\Entity\Board $board Board
     * @return void
     */
    public function setBoard(\JirasticBundle\Entity\Board $board = null)
    {
        $this->board = $board;
    }

    /**
     * @return \JirasticBundle\Entity\Board
     */
    public function getBoard()
    {
        return $this->board;
    }

    /**
     * @param \JirasticBundle\Entity\StatusMapping $statusMapping StatusMapping
     * @return void
     */
    public function addStatusMapping(\JirasticBundle\Entity\StatusMapping $statusMapping)
    {
        $this->statusMapping[] = $statusMapping;
    }

    /**
     * @param \JirasticBundle\Entity\StatusMapping $statusMapping StatusMapping
     * @return void
     */
    public function removeStatusMapping(\JirasticBundle\Entity\StatusMapping $statusMapping)
    {
        $this->statusMapping->removeElement($statusMapping);
    }

    /**
     * @return string representation of this class
     */
    public function __toString()
    {
        return (string) $this->title;
    }
}
