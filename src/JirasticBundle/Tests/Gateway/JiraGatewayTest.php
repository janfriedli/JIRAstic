<?php
/**
 * Jira Gateway Tests
 */

namespace JirasticBundle\Tests\Gateway;

use JirasticBundle\Gateway\JiraGateway;

/**
 * @package JirasticBundle\Tests\Gateway
 * @author   Jan Friedli <jan.friedli@swisscom.com>
 * @license  https://opensource.org/licenses/GPL-3.0 Public License
 * @link     http://www.swisscom.ch
 *
 * Class JiraGatewayTest
 */
class JiraGatewayTest extends \PHPUnit_Framework_TestCase
{

    /**
     * Test that the response is an array
     *
     * @return void
     */
    public function testResponseType()
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
            ->setConstructorArgs(array('http:/test.ch'))
            ->getMock();
        $guzzleMock->expects($this->once())
            ->method('get')
            ->will($this->returnvalue($requestMock));

        $jiraGateway = new JiraGateway('usr', 'pw', $guzzleMock);
        $response = $jiraGateway->getRequest('/yolo');

        $this->assertInternalType('array', $response->views);

    }

    /**
     * Test if the url is passed correctly
     *
     * @return void
     */
    public function testUrlPassedCorrectly()
    {
        $responseMock = $this->getMockBuilder('Guzzle\Http\Message\Response')
            ->setMethods(array('getBody'))
            ->setConstructorArgs(array(200))
            ->getMock();
        $responseMock->expects($this->once())
            ->method('getBody')
            ->will($this->returnvalue(json_encode($this->getTestArray())));

        $this->requestMock = $this->getMockBuilder('Guzzle\Http\Message\Request')
            ->setConstructorArgs(array('GET', 'http:/test.ch'))
            ->getMock();
        $this->requestMock->expects($this->once())
            ->method('send')
            ->will($this->returnvalue($responseMock));

        $guzzleMock = $this->getMockBuilder('Guzzle\Http\Client')
            ->setConstructorArgs(array('http:/test.ch'))
            ->getMock();
        $guzzleMock->expects($this->once())
            ->method('get')
            ->will($this->returnCallback(array($this, 'callbackForUrl')));

        $jiraGateway = new JiraGateway('usr', 'pw', $guzzleMock);
        $jiraGateway->getRequest('/yolo');
    }

    /**
     * Test if the Args are filled correctly
     *
     * @return void
     */
    public function testUrlArgsPassedCorrectly()
    {
        $responseMock = $this->getMockBuilder('Guzzle\Http\Message\Response')
            ->setMethods(array('getBody'))
            ->setConstructorArgs(array(200))
            ->getMock();
        $responseMock->expects($this->once())
            ->method('getBody')
            ->will($this->returnvalue(json_encode($this->getTestArray())));

        $this->requestMock = $this->getMockBuilder('Guzzle\Http\Message\Request')
            ->setConstructorArgs(array('GET', 'http:/test.ch'))
            ->getMock();
        $this->requestMock->expects($this->once())
            ->method('send')
            ->will($this->returnvalue($responseMock));

        $guzzleMock = $this->getMockBuilder('Guzzle\Http\Client')
            ->setConstructorArgs(array('http:/test.ch'))
            ->getMock();
        $guzzleMock->expects($this->once())
            ->method('get')
            ->will($this->returnCallback(array($this, 'callbackForUrlArgs')));

        $jiraGateway = new JiraGateway('usr', 'pw', $guzzleMock);
        $jiraGateway->getRequest('/yolo/%s?%d', array('tst', 1));

    }

    /**
     * Test if the username and password are set correctly
     *
     * @return void
     */
    public function testAuthArgsCorrect()
    {
        $responseMock = $this->getMockBuilder('Guzzle\Http\Message\Response')
            ->setMethods(array('getBody'))
            ->setConstructorArgs(array(200))
            ->getMock();
        $responseMock->expects($this->once())
            ->method('getBody')
            ->will($this->returnvalue(json_encode($this->getTestArray())));

        $this->requestMock = $this->getMockBuilder('Guzzle\Http\Message\Request')
            ->setConstructorArgs(array('GET', 'http:/test.ch'))
            ->getMock();
        $this->requestMock->expects($this->once())
            ->method('send')
            ->will($this->returnvalue($responseMock));
        $this->requestMock->expects($this->once())
            ->method('setAuth')
            ->will($this->returnCallback(array($this, 'callbackForAuth')));

        $guzzleMock = $this->getMockBuilder('Guzzle\Http\Client')
            ->setConstructorArgs(array('http:/test.ch'))
            ->getMock();
        $guzzleMock->expects($this->once())
            ->method('get')
            ->will($this->returnvalue($this->requestMock));

        $jiraGateway = new JiraGateway('usr', 'pw', $guzzleMock);
        $jiraGateway->getRequest('/yolo/%s?%d', array('tst', 1));
    }

    /**
     * Test that if error is thrown when supplying wrong number of params
     *
     * @expectedException PHPUnit_Framework_Error
     * @return void
     */
    public function testUrlArgsWrong()
    {
        $responseMock = $this->getMockBuilder('Guzzle\Http\Message\Response')
            ->setMethods(array('getBody'))
            ->setConstructorArgs(array(200))
            ->getMock();
        $responseMock->expects($this->never())
            ->method('getBody')
            ->will($this->returnvalue(json_encode($this->getTestArray())));

        $this->requestMock = $this->getMockBuilder('Guzzle\Http\Message\Request')
            ->setConstructorArgs(array('GET', 'http:/test.ch'))
            ->getMock();
        $this->requestMock->expects($this->never())
            ->method('send')
            ->will($this->returnvalue($responseMock));

        $guzzleMock = $this->getMockBuilder('Guzzle\Http\Client')
            ->setConstructorArgs(array('http:/test.ch'))
            ->getMock();
        $guzzleMock->expects($this->never())
            ->method('get')
            ->will($this->returnCallback(array($this, 'callbackForUrlArgsWrong')));

        $jiraGateway = new JiraGateway('usr', 'pw', $guzzleMock);
        $jiraGateway->getRequest('/yolo/%s?%d', array('tst'));
    }

    /**
     * @param string $username Username
     * @param string $password Password
     *
     * @return void
     */
    public function callbackForAuth($username, $password)
    {
        $this->assertEquals('usr', $username);
        $this->assertEquals('pw', $password);
    }

    /**
     * @param string $url The URL
     * @return object
     */
    public function callbackForUrl($url)
    {
        $this->assertInternalType('string', $url);
        $this->assertEquals('/yolo', $url);

        return $this->requestMock;
    }

    /**
     * @param string $url The URL
     * @return object
     */
    public function callbackForUrlArgs($url)
    {
        $this->assertEquals('/yolo/tst?1', $url);

        return $this->requestMock;
    }

    /**
     * Supply testdata
     *
     * @return array
     */
    private function getTestArray()
    {
        return array(
            'views' => array(
                array(
                    'id' => 48,
                    'name' => 'Board VR-Ablage',
                    'canEdit' => false
                ),
                array(
                    'id' => 42,
                    'name' => 'Test board',
                    'canEdit' => false
                ),
                array(
                    'id' => 418,
                    'name' => 'Yolo board',
                    'canEdit' => false
                ),

            )
        );
    }
}
