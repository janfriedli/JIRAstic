<?php
/**
 * Provides the connection to the API
 */
namespace JirasticBundle\Gateway;

use Guzzle\Http\Exception\RequestException;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;

/**
 * @package JirasticBundle\Gateway
 * @author   Jan Friedli <jan.friedli@swisscom.com>
 * @license  https://opensource.org/licenses/GPL-3.0 Public License
 * @link     http://www.swisscom.ch
 *
 * Class JiraGateway
 */
class JiraGateway
{
    /**
     * @var \Guzzle\Http\Client
     */
    protected $guzzle;

    /**
     * @var string
     */
    private $username;

    /**
     * @var string
     */
    private $password;

    /**
     * JiraGateway constructor.
     * @param string              $username Username
     * @param string              $password Password
     * @param \Guzzle\Http\Client $guzzle   Guzzle
     */
    public function __construct($username, $password, \Guzzle\Http\Client $guzzle)
    {
        $this->guzzle = $guzzle;
        $this->username = $username;
        $this->password = $password;
    }

    /**
     * Does a GET request towards the Jira API
     *
     * @param string $url    URL
     * @param array  $params Parameters
     * @return object
     */
    public function getRequest($url, $params = array())
    {
        try {

            $request = $this->guzzle->get(
                vsprintf(
                    $url,
                    $params
                )
            );
            $this->validateUrl($url, $params, $request);
            $request->setAuth($this->username, $this->password);
            $response = $request->send();

        } catch (RequestException $e) {
            throw new BadRequestHttpException('This Board does not support SCRUM');
        }

        $body = json_decode($response->getBody());

        return $body;
    }

    /**
     * Since vsprintf does not throw an error and I found no option for guzzle I use a custom validation
     * @todo it actually doesn't do a type check
     *
     * @param string  $url     URL
     * @param array   $params  Parameters
     * @param Request $request Request
     * @throws BadRequestHttpException
     * @return void
     */
    private function validateUrl($url, $params, $request)
    {
        if ($request->getUrl() === $url && !empty($params)) {
            if (substr_count($request->geturl()) === count($params)) {
                throw new BadRequestHttpException();
            }
        }
    }
}
