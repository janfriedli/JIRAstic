<?php
/**
 * Represents a Jira sprint
 */

namespace JirasticBundle\Prototype;

use JirasticBundle\Gateway\JiraGateway;
use JirasticBundle\Repository\Jira\IssueRepositoryInterface;
use JirasticBundle\Util\ConfigUtils;

/**
 * @package JirasticBundle\Prototype
 * @author   Jan Friedli <jan.friedli@swisscom.com>
 * @license  https://opensource.org/licenses/GPL-3.0 Public License
 * @link     http://www.swisscom.ch
 *
 * Class SprintPrototype
 */
class SprintPrototype
{
    /**
     * string URI for additional sprint infos
     */
    const ADDITIONAL_URI = '/rest/greenhopper/latest/rapid/charts/sprintreport?rapidViewId=%d&sprintId=%d';

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
     * @var array $allIssues All issues
     */
    private $allIssues;

    /**
     * @var JiraGateway $jiraGateway JiraGateway
     */
    private $jiraGateway;

    /**
     * @var ConfigUtils $configUtils Configutils
     */
    private $configUtils;

    /**
     * @var IssuePrototype $issueRepository Issue Repository
     */
    private $issueRepository;

    /**
     * SprintPrototype constructor.
     * @param JiraGateway              $jiraGateway     Jira Gateway
     * @param ConfigUtils              $configUtils     ConfigUtils
     * @param IssueRepositoryInterface $issueRepository Issue Repository
     */
    public function __construct(
        JiraGateway $jiraGateway,
        ConfigUtils $configUtils,
        IssueRepositoryInterface $issueRepository
    ) {
        $this->jiraGateway = $jiraGateway;
        $this->configUtils = $configUtils;
        $this->issueRepository = $issueRepository;
    }

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
        $this->loadAdditionalSprintData();
        return $this->issueRepository->getAllIssues($this->allIssues);

    }

    /**
     * Returns true if successful false if not and null when its something in between
     *
     * @return bool|null
     */
    public function isSuccessful()
    {
        $issues = $this->issueRepository->getAllIssues($this->allIssues);

        $closed= false;
        $resolved = false;
        $other = false;

        foreach ($issues as $issue) {
            //@todo remove hardcoded stuff later
            if ($issue['id'] == 'closed' && $issue['total'] > 0) {
                $closed = true;
            } elseif ($issue['id'] == 'resolved' && $issue['total'] > 0) {
                $resolved = true;
            } elseif ($issue['total'] > 0) {
                $other = true;
            }
        }

        /**
         * true = successful, false = not successful, null = something in between
         */
        if ($other) {
            return false;
        } elseif ($closed && $resolved) {
            return null;
        } elseif ($closed) {
            return true;
        }

        return false; // if there are none just say the sprint failed
    }

    /**
     * Load more necessary data
     *
     * @return void
     */
    private function loadAdditionalSprintData()
    {
        $sprintData = $this->jiraGateway->getRequest(
            self::ADDITIONAL_URI,
            array($this->boardId, $this->id)
        );

        $this->allIssues = array_merge(
            $sprintData->contents->completedIssues,
            $sprintData->contents->incompletedIssues
        );

        $this->startDate = $sprintData->sprint->startDate;
        $this->endDate = $sprintData->sprint->endDate;
    }
}
