<?php
/**
 * Represents a Jira issue
 */

namespace JirasticBundle\Prototype;

/**
 * @package JirasticBundle\Prototype
 * @author   Jan Friedli <jan.friedli@swisscom.com>
 * @license  https://opensource.org/licenses/GPL-3.0 Public License
 * @link     http://www.swisscom.ch
 *
 * Class IssuePrototype
 */
class IssuePrototype
{
    /**
     * @var int Issue id
     */
    private $id;

    /**
     * @var string Issue identifier
     */
    private $key;

    /**
     * @var string Summary of the issue
     */
    private $summary;

    /**
     * @var string Owner name of the issue
     */
    private $ownerName;

    /**
     * @var string Test instructions
     */
    private $testInstruction;

    /**
     * @var string Name of the creator
     */
    private $creatorName;

    /**
     * @var string Name of the assignee
     */
    private $assigneeName;

    /**
     * @var int Story Points
     */
    private $storyPoints;

    /**
     * @var int Estimated story points
     */
    private $storyPointsEstimate;

    /**
     * @var string Full description of the issue
     */
    private $description;

    /**
     * @return string
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * @param string $description Description
     * @return void
     */
    public function setDescription($description)
    {
        $this->description = $description;
    }

    /**
     * @return Int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param Int $id Id
     * @return void
     */
    public function setId($id)
    {
        $this->id = $id;
    }

    /**
     * @return String
     */
    public function getSummary()
    {
        return $this->summary;
    }

    /**
     * @param String $summary Summary
     * @return void
     */
    public function setSummary($summary)
    {
        $this->summary = $summary;
    }

    /**
     * @return String
     */
    public function getKey()
    {
        return $this->key;
    }

    /**
     * @param String $key Key
     * @return void
     */
    public function setKey($key)
    {
        $this->key = $key;
    }

    /**
     * @return String
     */
    public function getTestInstruction()
    {
        return $this->testInstruction;
    }

    /**
     * @param string $testInstruction Testinstruction
     * @return void
     */
    public function setTestInstruction($testInstruction)
    {
        $this->testInstruction = $testInstruction;
    }

    /**
     * @return String
     */
    public function getCreatorName()
    {
        return $this->creatorName;
    }

    /**
     * @param String $creatorName Creator
     * @return void
     */
    public function setCreatorName($creatorName)
    {
        $this->creatorName = $creatorName;
    }

    /**
     * @return String
     */
    public function getAssigneeName()
    {
        return $this->assigneeName;
    }

    /**
     * @param String $assigneeName Assignee
     * @return void
     */
    public function setAssigneeName($assigneeName)
    {
        $this->assigneeName = $assigneeName;
    }

    /**
     * @return Int
     */
    public function getStoryPoints()
    {
        return $this->storyPoints;
    }

    /**
     * @param Int $storyPoints Story Points
     * @return void
     */
    public function setStoryPoints($storyPoints)
    {
        $this->storyPoints = $storyPoints;
    }

    /**
     * @return int storyPointsEstimate
     */
    public function getStoryPointsEstimate()
    {
        return $this->storyPointsEstimate;
    }

    /**
     * @param int $storyPointsEstimate storyPointsEstimate
     * @return void
     */
    public function setStoryPointsEstimate($storyPointsEstimate)
    {
        $this->storyPointsEstimate = $storyPointsEstimate;
    }

    /**
     * @return String
     */
    public function getOwnerName()
    {
        $this->ownerName;
    }

    /**
     * @param string $ownerName Owner name
     * @return void
     */
    public function setOwnerName($ownerName)
    {
        $this->ownerName = $ownerName;
    }
}
