<?php
/**
 * Represents a Jira board
 */

namespace JirasticBundle\Model;

/**
 * @package JirasticBundle\Model
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
        return $this->sprints;
    }
}

