<?php
/**
 * Represents a Jira board
 */

namespace JirasticBundle\Prototype;

use JirasticBundle\Repository\Jira\SprintRepository;
use JirasticBundle\Repository\Jira\SprintRepositoryInterface;

/**
 * @package JirasticBundle\Prototype
 * @author   Jan Friedli <jan.friedli@swisscom.com>
 * @license  https://opensource.org/licenses/GPL-3.0 Public License
 * @link     http://www.swisscom.ch
 *
 * Class BoardPrototype
 */
class BoardPrototype
{
    /**
     * @var array $sprints Sprints of the current Board
     */
    private $sprints;

    /**
     * @var int $id Board Id
     */
    private $id;

    /**
     * @var string $title Board title
     */
    private $title;

    /**
     * @var SprintRepository $sprintRepository Sprint repository
     */
    private $sprintRepository;

    /**
     * BoardPrototype constructor.
     * @param SprintRepositoryInterface $sprintRepository Sprint repository
     */
    public function __construct(SprintRepositoryInterface $sprintRepository)
    {
        $this->sprintRepository = $sprintRepository;
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
     * Returns all sprints of the board
     *
     * @return array
     */
    public function getSprints()
    {
        if (!$this->sprints) {
            $this->initSprints();
        }

        return $this->sprints;
    }

    /**
     * Load sprints from Jira API
     *
     * @return void
     */
    private function initSprints()
    {
        $this->sprintRepository->setBoardId($this->id);
        $this->sprints = $this->sprintRepository->getAllSprints();
    }

    /**
     * Returns the fitting Sprint or null if it doesn't exist
     *
     * @param int $sprintId Sprint Id
     * @return null|Sprint
     */
    public function getSprintById($sprintId)
    {
        //@todo find a better solution for setBoardId()
        $this->sprintRepository->setBoardId($this->id);
        return $this->sprintRepository->getSprintById($sprintId);
    }
}
