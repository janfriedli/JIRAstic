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
     * LoadBoardData constructor.
     * @param JiraGateway   $jiraGateway   JiraGateway
     * @param ObjectManager $entityManager The entity manager
     * @param string        $rootDir       Application root path
     */
    public function __construct(JiraGateway $jiraGateway, ObjectManager $entityManager, $rootDir)
    {
        $this->jiraGateway = $jiraGateway;
        $this->entityManager = $entityManager;
        $this->rootDir = $rootDir;
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
                $this->entityManager->persist($board);
            }
        }

        $this->entityManager->flush();

        if ($emptyDb) {
            $process = new Process('php '.$this->rootDir.'/console doctrine:fixtures:load --no-interaction --append');
            $process->run();
            if (!$process->isSuccessful()) {
                throw new ProcessFailedException($process);
            }
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
        $flag = $this->entityManager->getRepository('JirasticBundle:Board')->findOneBy(array('jiraId' => $board->id));
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
        $result = $this->entityManager->getRepository('JirasticBundle:Board')->findAll();
        if ($result) {
            return false;
        }

        return true;
    }
}
