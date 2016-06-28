<?php
/**
 * Customfield
 */
namespace JirasticBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Table(name="customfield")
 * @ORM\Entity(repositoryClass="JirasticBundle\Repository\CustomfieldRepository")
 * @package JirasticBundle\Repository
 * @author   Jan Friedli <jan.friedli@swisscom.com>
 * @license  https://opensource.org/licenses/GPL-3.0 Public License
 * @link     http://www.swisscom.ch
 *
 * Class Customfield
 */
class Customfield
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
     * @ORM\Column(name="test_instructions", type="string", length=100, unique=false)
     * @Assert\NotBlank()
     * @Assert\Type(
     *     type="string",
     *     message="This value needs to be a string"
     * )
     */
    private $testInstructions;

    /**
     * @var string
     *
     * @ORM\Column(name="story_points", type="string", length=100, unique=false)
     * @Assert\NotBlank()
     * @Assert\Type(
     *     type="string",
     *     message="This value needs to be a string"
     * )
     */
    private $storyPoints;

    /**
     * @var string
     *
     * @ORM\Column(name="story_points_estimated", type="string", length=100, unique=false)
     * @Assert\NotBlank()
     * @Assert\Type(
     *     type="string",
     *     message="This value needs to be a string"
     * )
     */
    private $storyPointsEstimated;

    /**
     * @var string
     *
     * @ORM\Column(name="story_owner", type="string", length=255, unique=false)
     * @Assert\NotBlank()
     * @Assert\Type(
     *     type="string",
     *     message="This value needs to be a string"
     * )
     */
    private $storyOwner;

    /**
     * @ORM\ManyToOne(targetEntity="User", inversedBy="customfields")
     * @ORM\JoinColumn(name="user_id", referencedColumnName="id")
     */
    private $user;


    /**
     * Get id
     *
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set testInstructions
     *
     * @param string $testInstructions The TestIsntructions
     * @return Customfield
     */
    public function setTestInstructions($testInstructions)
    {
        $this->testInstructions = $testInstructions;

        return $this;
    }

    /**
     * Get testInstructions
     *
     * @return string
     */
    public function getTestInstructions()
    {
        return $this->testInstructions;
    }

    /**
     * Set storyPoints
     *
     * @param string $storyPoints Storypoints
     * @return Customfield
     */
    public function setStoryPoints($storyPoints)
    {
        $this->storyPoints = $storyPoints;

        return $this;
    }

    /**
     * Get storyPoints
     *
     * @return string
     */
    public function getStoryPoints()
    {
        return $this->storyPoints;
    }

    /**
     * Set storyPointsEstimated
     *
     * @param string $storyPointsEstimated StoryPointsEstimated
     * @return Customfield
     */
    public function setStoryPointsEstimated($storyPointsEstimated)
    {
        $this->storyPointsEstimated = $storyPointsEstimated;

        return $this;
    }

    /**
     * Get storyPointsEstimated
     *
     * @return string
     */
    public function getStoryPointsEstimated()
    {
        return $this->storyPointsEstimated;
    }

    /**
     * Set storyOwner
     *
     * @param string $storyOwner StoryOwner
     * @return Customfield
     */
    public function setStoryOwner($storyOwner)
    {
        $this->storyOwner = $storyOwner;

        return $this;
    }

    /**
     * Get storyOwner
     *
     * @return string
     */
    public function getStoryOwner()
    {
        return $this->storyOwner;
    }

    /**
     * Set user
     *
     * @param \JirasticBundle\Entity\User $user User
     * @return Customfield
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
