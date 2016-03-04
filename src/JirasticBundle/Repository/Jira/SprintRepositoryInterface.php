<?php
/**
 * Interface to make sure every sprint repository behaves the same
 */

namespace JirasticBundle\Repository\Jira;

/**
 * Interface SprintRepositoryInterface
 * @package JirasticBundle\Repository
 * @author   Jan Friedli <jan.friedli@swisscom.com>
 * @license  https://opensource.org/licenses/GPL-3.0 Public License
 * @link     http://www.swisscom.ch
 */
interface SprintRepositoryInterface
{
    /**
     * Returns all sprints
     *
     * @return array
     */
    public function getAllSprints();

    /**
     * Returns an one sprint
     *
     * @param int $id Sprint Id
     * @return Sprint
     */
    public function getSprintById($id);

    /**
     * A Sprint needs to know to which board it belongs to
     *
     * @param int $boardId Board Id
     * @return void
     */
    public function setBoardId($boardId);
}
