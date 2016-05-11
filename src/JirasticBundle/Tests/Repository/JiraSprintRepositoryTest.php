<?php
/**
 * Jira Sprint Repo Tests
 */

namespace JirasticBundle\Tests\Repository;

use Guzzle\Http\Client;
use HWI\Bundle\OAuthBundle\Security\Core\Authentication\Token\OAuthToken;
use JirasticBundle\Gateway\JiraGateway;
use JirasticBundle\Prototype\SprintPrototype;
use JirasticBundle\Repository\Jira\SprintRepository;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorage;

/**
 * @package JirasticBundle\Tests\Repository
 * @author   Jan Friedli <jan.friedli@swisscom.com>
 * @license  https://opensource.org/licenses/GPL-3.0 Public License
 * @link     http://www.swisscom.ch
 *
 * Class Jira Sprint Repo Tests
 */
class JiraSprintRepositoryTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var SprintRepository
     */
    private $sprintRepository;

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

        $configUtils = $this->getMockBuilder('JirasticBundle\Util\ConfigUtils')
            ->disableOriginalConstructor()
            ->getMock();

        $issueRepo = $this->getMockBuilder('JirasticBundle\Repository\Jira\IssueRepository')
            ->disableOriginalConstructor()
            ->getMock();

        $jiraGateway = $this->getJiraGateway($guzzleMock);
        $sprintPrototype = new SprintPrototype($jiraGateway, $configUtils, $issueRepo);

        $this->sprintRepository = new SprintRepository($jiraGateway, $sprintPrototype, 6);
    }

    /**
     * Test returning sprint objects
     *
     * @return void
     */
    public function testGetAllSprints()
    {
        $result = $this->sprintRepository->getAllSprints();
        $this->assertEquals($result[0]->getId(), 501);
        $this->assertEquals($result[0]->getTitle(), 'Sprint 26');

        $this->assertEquals($result[1]->getId(), 301);
        $this->assertEquals($result[1]->getTitle(), 'Sprint 25');
        $this->assertCount(2, $result);
    }

    /**
     * Test finding one board
     *
     * @return void
     */
    public function testGetSprintById()
    {
        $result = $this->sprintRepository->getSprintById(301);
        $this->assertEquals($result->getId(), 301);
        $this->assertEquals($result->getTitle(), 'Sprint 25');
    }

    /**
     * Test not existing SprintId
     *
     * @return void
     */
    public function testWrongId()
    {
        $result = $this->sprintRepository->getSprintById(3201);
        $this->assertNull($result);
    }

    /**
     * Test string type
     *
     * @return void
     */
    public function testStringId()
    {
        $result = $this->sprintRepository->getSprintById("301");
        $this->assertEquals($result->getId(), 301);
        $this->assertEquals($result->getTitle(), 'Sprint 25');
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
                    "id" => 301,
                    "self" => "https://issue.swisscom.ch/rest/agile/1.0/sprint/301",
                    "state" => "closed",
                    "name"=> "Sprint 25",
                    "startDate" => "2030-06-02T16:29:18.159+02:00",
                    "endDate" => "2030-06-13T16:29:18.159+02:00",
                    "completeDate"=> "2014-06-16T10:25:38.392+02:00"
                ),
                array(
                    "id" => 501,
                    "self" => "https://issue.swisscom.ch/rest/agile/1.0/sprint/301",
                    "state" => "closed",
                    "name"=> "Sprint 26",
                    "startDate" => "2030-06-02T16:29:18.159+02:00",
                    "endDate" => "2030-06-13T16:29:18.159+02:00",
                    "completeDate"=> "2014-06-16T10:25:38.392+02:00"
                ),
                array(
                    "id" => "​601",
                    "self" => "https://issue.swisscom.ch/rest/agile/1.0/sprint/301",
                    "state" => "closed",
                    "name"=> "Sprint 25",
                    "startDate" => "2014-06-02T16:29:18.159+02:00",
                    "endDate" => "2014-06-13T16:29:18.159+02:00",
                    "completeDate"=> "2014-06-16T10:25:38.392+02:00"
                )
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
