<?php
/**
 * BoardRepository Interface
 */

namespace JirasticBundle\Repository\Jira;

/**
 * Interface to make sure every board repository behaves the same
 * @package JirasticBundle\Repository
 * @author   Jan Friedli <jan.friedli@swisscom.com>
 * @license  https://opensource.org/licenses/GPL-3.0 Public License
 * @link     http://www.swisscom.ch
 */
interface JiraBoardRepositoryInterface
{
    /**
     * Returns all boards
     *
     * @return array
     */
    public function getAllBoards();

    /**
     * Returns an one board
     *
     * @param int $id Board id
     * @return Board
     */
    public function getBoardById($id);
}
