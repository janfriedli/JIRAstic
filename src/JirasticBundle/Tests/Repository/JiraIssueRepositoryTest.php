<?php
/**
 * Jira Issue Repo Tests
 */

namespace JirasticBundle\Tests\Repository;

use Guzzle\Http\Client;
use HWI\Bundle\OAuthBundle\Security\Core\Authentication\Token\OAuthToken;
use JirasticBundle\Gateway\JiraGateway;
use JirasticBundle\Prototype\IssuePrototype;
use JirasticBundle\Repository\Jira\IssueRepository;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorage;

/**
 * @package JirasticBundle\Tests\Repository
 * @author   Jan Friedli <jan.friedli@swisscom.com>
 * @license  https://opensource.org/licenses/GPL-3.0 Public License
 * @link     http://www.swisscom.ch
 *
 * Class Jira Issue Repo Tests
 */
class JiraIssueRepositoryTest extends \PHPUnit_Framework_TestCase
{

    /**
     * @var IssueRepository
     */
    private $issueRepository;
    
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
            ->will($this->returnvalue($this->getTestData()));

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
        
        $configUtils->method('getStatusMapping')->willReturn(
            [
                1 =>     'open'
            ]
        );

        $configUtils->method('getMappedIssues')->willReturn(
            [
                'open' => [
                    'id' => 'open',
                    'title' => 'unresolved stories',
                    'titleShort' => 'unresolved',
                    'icon' => 'fa-exclamation-triangle',
                    'class' => 'unresolved',
                    'bgcolor' => '#b91d47',
                    'issues' => []
                ]
            ]
        );

        $issueRepo = $this->getMockBuilder('JirasticBundle\Repository\Jira\IssueRepository')
            ->disableOriginalConstructor()
            ->getMock();

        $jiraGateway = $this->getJiraGateway($guzzleMock);
        $issuePrototype = new IssuePrototype($jiraGateway, $configUtils, $issueRepo);

        $this->issueRepository = new IssueRepository($jiraGateway, $issuePrototype, $configUtils);
    }

    /**
     * Test returning Issue objects
     *
     * @return void
     */
    public function testGetAllIssues()
    {
        $t = new \stdClass();
        $t->key = "key";
        $result = $this->issueRepository->getAllIssues(array($t))['open'];
        $this->assertEquals($result['id'], 'open');
        $this->assertEquals($result['title'], 'unresolved stories');
        $this->assertEquals($result['titleShort'], 'unresolved');
        $this->assertEquals($result['class'], 'unresolved');
        $this->assertEquals($result['icon'], 'fa-exclamation-triangle');
        $this->assertEquals($result['bgcolor'], '#b91d47');
        $this->assertCount($result['total'], $result['issues']);

        $issue = $result['issues'][0];
        $this->assertEquals('1139789', $issue->getId());
        $this->assertEquals('EVO-3180', $issue->getKey());
        $this->assertEquals(
            "As a Developer I want the NIOS worker to set the status to done if nothing needs to be done",
            $issue->getSummary()
        );
        $this->assertEquals('Ackermann Samuel, ENT-BAN', $issue->getCreatorName());
        $this->assertEquals('description1', $issue->getDescription());

    }

    /**
     * Supply testdata
     *
     * @return array
     */
    private function getTestData()
    {
        return file_get_contents(__DIR__.'/Issues.json');
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
