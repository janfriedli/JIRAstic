<?php
/**
 * Jira Gateway Tests
 */

namespace JirasticBundle\Tests\Repository;

use Guzzle\Http\Client;
use HWI\Bundle\OAuthBundle\Security\Core\Authentication\Token\OAuthToken;
use JirasticBundle\Gateway\JiraGateway;
use JirasticBundle\Prototype\BoardPrototype;
use JirasticBundle\Repository\Jira\JiraBoardRepository;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorage;

/**
 * @package JirasticBundle\Tests\Repository
 * @author   Jan Friedli <jan.friedli@swisscom.com>
 * @license  https://opensource.org/licenses/GPL-3.0 Public License
 * @link     http://www.swisscom.ch
 *
 * Class Jira Board Repo Tests
 */
class JiraBoardRepositoryTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var JiraBoardRepository
     */
    private $boardRepository;

    /**
     * prepare Baord Repo
     * @return void
     */
    public function setUp()
    {
        $responseMock = $this->getMockBuilder('Guzzle\Http\Message\Response')
            ->setMethods(array('getBody'))
            ->setConstructorArgs(array(200))
            ->getMock();
        $responseMock->expects($this->once())
            ->method('getBody')
            ->will($this->returnvalue(json_encode($this->getTestArray())));

        $requestMock = $this->getMockBuilder('Guzzle\Http\Message\Request')
            ->setConstructorArgs(array('GET', 'http:/test.ch'))
            ->getMock();
        $requestMock->expects($this->once())
            ->method('send')
            ->will($this->returnvalue($responseMock));

        $guzzleMock = $this->getMockBuilder('Guzzle\Http\Client')
            ->setConstructorArgs(array('http://test.ch'))
            ->getMock();
        $guzzleMock->expects($this->once())
            ->method('get')
            ->will($this->returnvalue($requestMock));

        $sprintRepo = $this->getMockBuilder('JirasticBundle\Repository\Jira\SprintRepository')
            ->disableOriginalConstructor()
            ->getMock();

        $boardProto = new BoardPrototype($sprintRepo);
        $this->boardRepository = new JiraBoardRepository($this->getJiraGateway($guzzleMock), $boardProto);
    }

    /**
     * Test returning board objects
     *
     * @return void
     */
    public function testGetAllBoards()
    {
        $result = $this->boardRepository->getAllBoards();
        $this->assertEquals($result[0]->getId(), 48);
        $this->assertEquals($result[0]->getTitle(), 'Board VR-Ablage');
    }

    /**
     * Test finding one board
     *
     * @return void
     */
    public function testGetBoardyById()
    {
        $result = $this->boardRepository->getBoardById('42');
        $this->assertEquals($result->getId(), 42);
        $this->assertEquals($result->getTitle(), 'Test board');
    }

    /**
     * Supply testdata
     *
     * @return array
     */
    private function getTestArray()
    {
        return array(
            'values' => array(
                array(
                    'id' => 48,
                    'name' => 'Board VR-Ablage'
                ),
                array(
                    'id' => 42,
                    'name' => 'Test board'
                ),
                array(
                    'id' => 418,
                    'name' => 'Yolo board'
                ),

            )
        );
    }

    /**
     * @return TokenStorage
     */
    private function getTokenStorage()
    {
        $token = new OAuthToken(array('oauth_token' => 'oauthToken', 'oauth_token_secret' => 'secret'));
        $toStorage = new TokenStorage();
        $toStorage->setToken($token);
        return $toStorage;
    }

    /**
     * @param Client $guzzleMock Guzzle Mock
     * @return JiraGateway
     */
    private function getJiraGateway($guzzleMock)
    {
        return new JiraGateway(
            $guzzleMock,
            $this->getTokenStorage(),
            'somePath/to/a/file.pem',
            'key',
            'secret'
        );
    }
}
