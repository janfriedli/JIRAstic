<?php
/**
 * Sprint Repository provides data for Sprints
 */

namespace JirasticBundle\Repository\Jira;

use JirasticBundle\Gateway\JiraGateway;
use JirasticBundle\Prototype\SprintPrototype;
use JirasticBundle\Repository\Jira\SprintRepositoryInterface;

/**
 * @package JirasticBundle\Repository
 * @author   Jan Friedli <jan.friedli@swisscom.com>
 * @license  https://opensource.org/licenses/GPL-3.0 Public License
 * @link     http://www.swisscom.ch
 *
 * Class SprintRepository
 */
class SprintRepository implements SprintRepositoryInterface
{

    /**
     * URI for Sprints
     * /rest/greenhopper/latest/sprintquery/%d?includeHistoricSprints=true&includeFutureSprints=false
     */
    const URI = '/rest/agile/1.0/board/%d/sprint?maxResults=50&state=active,closed';

    /**
     * @var JiraGateway
     */
    private $jiraGateway;

    /**
     * @var array
     */
    private $sprints;

    /**
     * @var SprintPrototype
     */
    private $sprintPrototype;

    /**
     * @var int
     */
    private $boardId;

    /**
     * @var int
     */
    private $timeBack;
    /**
     * SprintRepository constructor.
     * @param JiraGateway     $jiraGateway     Gateway
     * @param SprintPrototype $sprintPrototype Sprint Prototype
     * @param int             $timeBack        How many months back the sprints will be filtered
     */
    public function __construct(JiraGateway $jiraGateway, SprintPrototype $sprintPrototype, $timeBack)
    {
        $this->jiraGateway = $jiraGateway;
        $this->sprintPrototype = $sprintPrototype;
        $this->timeBack = $timeBack;
    }

    /**
     * initialize all Sprints
     *
     * @return void
     */
    private function initSprints()
    {
        $response = $this->jiraGateway->getRequest(self::URI, array($this->boardId))->values;
        $sprints = array();
        $response = $this->filterSprints($response);

        foreach ($response as $jiraSprint) {
            $sprint = clone $this->sprintPrototype;
            $sprint->setId($jiraSprint->id);
            $sprint->setTitle($jiraSprint->name);
            $sprint->setState($jiraSprint->state);
            $sprint->setBoardId($this->boardId);
            array_push($sprints, $sprint);
        }
        $this->sprints = array_reverse($sprints);
    }

    /**
     * Returns all Sprints
     *
     * @return array
     */
    public function getAllSprints()
    {
        if (!$this->sprints) {
            $this->initSprints();
        }

        return $this->sprints;
    }

    /**
     * Returns one Sprint
     *
     * @param int $id Id
     * @return Sprint
     */
    public function getSprintById($id)
    {
        foreach ($this->getAllSprints() as $sprint) {
            if ($sprint->getId() === intval($id)) {
                return $sprint;
            }
        }
    }

    /**
     * @param int $id Id
     * @return void
     */
    public function setBoardId($id)
    {
        $this->boardId = $id;
    }

    /**
     * @param array $sprints Sprints
     * @return array
     */
    private function filterSprints($sprints)
    {
        return array_filter(
            $sprints,
            function ($sprint) {

                $sprintDate = date("Y-m-d", strtotime($sprint->startDate));
                $timeBack = date("Y-m-d", strtotime("-".$this->timeBack." month"));

                if ($sprintDate > $timeBack) {
                    return true;
                }

                return false;
            }
        );
    }
}
