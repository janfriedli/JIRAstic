<?php
/**
 * Represents a Jira sprint
 */

namespace JirasticBundle\Model;

use JirasticBundle\Gateway\JiraGateway;
use JirasticBundle\Model\Issue;
use JirasticBundle\Repository\IssueRepositoryInterface;
use JirasticBundle\Util\ConfigUtils;

/**
 * @package JirasticBundle\Model
 * @author   Jan Friedli <jan.friedli@swisscom.com>
 * @license  https://opensource.org/licenses/GPL-3.0 Public License
 * @link     http://www.swisscom.ch
 *
 * Class SprintPrototype
 */
class SprintPrototype
{
    /**
     * @var Issue $issues  Issues of the current sprint
     */
    private $issues;

    /**
     * @var string $title Sprint title
     */
    private $title;

    /**
     * @var int $id Sprint id
     */
    private $id;

    /**
     * @var string $state State of the Sprint
     */
    private $state;

    /**
     * @var int $boardId The id of the board this sprint belongs to
     */
    private $boardId;

    /**
     * @var string $startDate The start date
     */
    private $startDate;

    /**
     * @var string $endDate The end date
     */
    private $endDate;


    /**
     * @param int $boardId Board id
     * @return void
     */
    public function setBoardId($boardId)
    {
        $this->boardId = $boardId;
    }

    /**
     * @return String
     */
    public function getStartDate()
    {
        return $this->startDate;
    }

    /**
     * @return String
     */
    public function getEndDate()
    {
        return $this->endDate;
    }

    /**
     * @return string
     */
    public function getState()
    {
        return $this->state;
    }

    /**
     * @param string $state State
     * @return void
     */
    public function setState($state)
    {
        $this->state = $state;
    }

    /**
     * @return String
     */
    public function getTitle()
    {
        return $this->title;
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
     * @return Int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param int $id Id
     * @return void
     */
    public function setId($id)
    {
        $this->id = $id;
    }

    /**
     * Returns the issues of the current sprint
     *
     * @return array
     */
    public function getIssues()
    {
        return $this->issues;

    }
}
