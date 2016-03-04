<?php
/**
 * Handles the interaction with the Jira API
 */

namespace JirasticBundle\Service;

use JirasticBundle\Repository\Jira\JiraBoardRepositoryInterface;
use JirasticBundle\Repository\Jira\JiraBoardRepository;

/**
 * @package JirasticBundle\Service
 * @author   Jan Friedli <jan.friedli@swisscom.com>
 * @license  https://opensource.org/licenses/GPL-3.0 Public License
 * @link     http://www.swisscom.ch
 *
 * Class JiraAPI
 */
class JiraAPI
{
    /**
     * @var array $boards Array of boards
     */
    private $boards;

    /**
     * @var JiraBoardRepository $jiraBoardRepository Board Repository
     */
    private $jiraBoardRepository;

    /**
     * JiraAPI constructor.
     * @param JiraBoardRepositoryInterface $jiraBoardRepository Board Repository
     */
    public function __construct(JiraBoardRepositoryInterface $jiraBoardRepository)
    {
        $this->jiraBoardRepository = $jiraBoardRepository;
    }

    /**
     * @return array
     */
    public function getBoards()
    {
        if (!$this->boards) {
            $this->initBoards();
        }

        return $this->boards;
    }

    /**
     * Returns sprints of a given board
     *
     * @param int $boardId BoardId
     * @return null|BoardPrototype
     */
    public function getSprintsOfBoard($boardId)
    {
        return $this->getBoardById($boardId)->getSprints();
    }

    /**
     * Get Board depending on its id
     *
     * @param int $boardId BoardId
     * @return null
     */
    public function getBoardById($boardId)
    {
        return $this->jiraBoardRepository->getBoardById(intval($boardId));
    }

    /**
     * Load all boards from the Jira API
     * @return void
     */
    public function initBoards()
    {
        $this->boards = $this->jiraBoardRepository->getAllBoards();
    }
}
