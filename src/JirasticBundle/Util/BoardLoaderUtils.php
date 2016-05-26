<?php
/**
 * Initial Data for Boards
 */
namespace JirasticBundle\Util;

use Doctrine\Common\Persistence\ObjectManager;
use JirasticBundle\Entity\Board;
use JirasticBundle\Gateway\JiraGateway;
use MyProject\Proxies\__CG__\stdClass;
use Symfony\Component\Process\Process;
use Symfony\Component\Process\Exception\ProcessFailedException;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

/**
 * @package JirasticBundle\Util
 * @author   Jan Friedli <jan.friedli@swisscom.com>
 * @license  https://opensource.org/licenses/GPL-3.0 Public License
 * @link     http://www.swisscom.ch
 *
 * Class BoardLoaderUtils
 */
class BoardLoaderUtils
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
     * @var ObjectManager
     */
    private $entityManager;

    /**
     * @var string
     */
    private $rootDir;

    /**
     * @var string
     */
    private $token;

    /**
     * LoadBoardData constructor.
     * @param JiraGateway           $jiraGateway   JiraGateway
     * @param ObjectManager         $entityManager The entity manager
     * @param string                $rootDir       Application root path
     * @param TokenStorageInterface $tokenStorage  Token storage
     */
    public function __construct(
        JiraGateway $jiraGateway,
        ObjectManager $entityManager,
        $rootDir,
        TokenStorageInterface $tokenStorage
    ) {
        $this->jiraGateway = $jiraGateway;
        $this->entityManager = $entityManager;
        $this->rootDir = $rootDir;
        $this->token = $tokenStorage;
    }

    /**
     * Load missing boards into DB
     *
     * @return void
     */
    public function load()
    {
        $response = $this->jiraGateway->getRequest(self::URI);
        $emptyDb = $this->checkIfDbIsEmtpy();

        foreach ($response->values as $jiraBoard) {
            if (!$this->checkIfExistInDb($jiraBoard)) {
                $board = new Board();
                $board->setName($jiraBoard->name);
                $board->setJiraId($jiraBoard->id);
                $board->setUser($this->token->getToken()->getUser());
                $board->setLastModified(new \DateTime());
                $this->entityManager->persist($board);
            }
        }

        $this->entityManager->flush();

        if (!$emptyDb) {
            $this->removeDeletedBoards($response->values);
        }

    }

    /**
     * make sure entry doesn't exist already
     *
     * @param stdClass $board Board
     * @return bool
     */
    private function checkIfExistInDb($board)
    {
        $flag = $this->entityManager
            ->getRepository('JirasticBundle:Board')
            ->findOneBy(array('jiraId' => $board->id, 'user' => $this->token->getToken()->getUser()));

        if ($flag) {
            return true;
        }

        return false;
    }

    /**
     * @return bool
     */
    private function checkIfDbIsEmtpy()
    {
        $result = $this->entityManager
            ->getRepository('JirasticBundle:Board')
            ->findByUser($this->token->getToken()->getUser());
        if ($result) {
            return false;
        }

        return true;
    }

    /**
     * @param array $jiraBoards Reponse from JIRA
     * @return void
     */
    private function removeDeletedBoards($jiraBoards)
    {
        $dbIds = $this->getBoardsIds();
        $jiraIds = array();

        foreach ($jiraBoards as $board) {
            array_push($jiraIds, $board->id);
        }

        $diffIds = array_diff($dbIds, $jiraIds);
        $entityManager = $this->entityManager;
        foreach ($diffIds as $diffId) {
            $board = $entityManager->getRepository('JirasticBundle:Board')
                ->findOneBy(
                    array('jiraId' => $diffId, 'user' => $this->token->getToken()->getUser())
                );
            $board->removeStatuses();
            $entityManager->remove($board);
        }
        $entityManager->flush();
    }

    /**
     * @return array
     */
    private function getBoardsIds()
    {
        $user = $this->token->getToken()->getUser();
        $dbBoards = $this->entityManager->getRepository('JirasticBundle:Board')->findByUser($user);

        $ids = array();
        foreach ($dbBoards as $board) {
            array_push($ids, $board->getJiraId());
        }

        return $ids;
    }
}
