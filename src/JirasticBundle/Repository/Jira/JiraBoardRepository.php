<?php
/**
 * Board Repository provides data for boards
 */

namespace JirasticBundle\Repository\Jira;

use Doctrine\ORM\EntityManager;
use JirasticBundle\Entity\Board;
use JirasticBundle\Gateway\JiraGateway;
use JirasticBundle\Prototype\BoardPrototype;
use JirasticBundle\Repository\Jira\JiraBoardRepositoryInterface;

/**
 * @package JirasticBundle\Repository
 * @author   Jan Friedli <jan.friedli@swisscom.com>
 * @license  https://opensource.org/licenses/GPL-3.0 Public License
 * @link     http://www.swisscom.ch
 *
 * Class JiraBoardRepository
 */
class JiraBoardRepository implements JiraBoardRepositoryInterface
{

    /**
     * URI for boards
     * Another possible endpoint: /rest/greenhopper/1.0/rapidview
     */
    const URI = '/rest/agile/1.0/board?type=scrum';

    /**
     * @var JiraGateway
     */
    private $jiraGateway;

    /**
     * @var array
     */
    private $boards;

    /**
     * @var BoardPrototype
     */
    private $boardPrototype;
    
    /**
     * JiraBoardRepository constructor.
     * @param JiraGateway    $jiraGateway    Gateway
     * @param BoardPrototype $boardPrototype Board Prototype
     */
    public function __construct(JiraGateway $jiraGateway, BoardPrototype $boardPrototype)
    {
        $this->jiraGateway = $jiraGateway;
        $this->boardPrototype = $boardPrototype;
    }

    /**
     * initialize all boards
     *
     * @return void
     */
    private function initBoards()
    {
        $boardsJira = $this->jiraGateway->getRequest(self::URI)->values;
        $boards = array();
        foreach ($boardsJira as $jiraBoard) {
            $board = clone $this->boardPrototype;
            $board->setTitle($jiraBoard->name);
            $board->setId($jiraBoard->id);
            array_push($boards, $board);
        }
        $this->boards = $boards;
    }

    /**
     * Returns all boards
     *
     * @return array
     */
    public function getAllBoards()
    {
        if (!$this->boards) {
            $this->initBoards();
        }

        return $this->boards;
    }

    /**
     * Get a board by it's id
     *
     * @param int $id Id
     * @return Board
     */
    public function getBoardById($id)
    {
        foreach ($this->getAllBoards() as $board) {
            if (intval($id) === $board->getId()) {
                return $board;
            }
        }
    }
}
