<?php
/**
 * Provides the connection to the API
 */
namespace JirasticBundle\Gateway;

use Guzzle\Http\Client;
use Guzzle\Http\Exception\RequestException;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Guzzle\Plugin\Oauth\OauthPlugin;

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
    private $token;

    /**
     * @var string
     */
    private $privateKeyPath;

    /**
     * @var string
     */
    private $consumerKey;

    /**
     * @var string
     */
    private $consumerSecret;


    /**
     * JiraGateway constructor.
     * @param Client                $guzzle         Guzzle client
     * @param TokenStorageInterface $tokenStorage   Token storage
     * @param string                $privateKeyPath Path to private key
     * @param string                $consumerKey    Consumer key
     * @param string                $consumerSecret Consumer secret
     */
    public function __construct(
        \Guzzle\Http\Client $guzzle,
        TokenStorageInterface $tokenStorage,
        $privateKeyPath,
        $consumerKey,
        $consumerSecret
    ) {
        $this->guzzle = $guzzle;
        $this->token = $tokenStorage;
        $this->privateKeyPath = $privateKeyPath;
        $this->consumerKey = $consumerKey;
        $this->consumerSecret = $consumerSecret;
        $this->prepareClientForOauth();
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
            $response = $request->send();

        } catch (RequestException $e) {
            throw $e;
        }

        $body = json_decode($response->getBody());

        return $body;
    }

    /**
     * @return Client Prepares the client to sign OAuth requests
     */
    public function prepareClientForOauth()
    {
        $privateKey = $this->privateKeyPath;
        //If you're logged out getRawToken does not exist...
        if(method_exists($this->token->getToken(), 'getRawToken')) {
            $rawToken = $this->token->getToken()->getRawToken();

            $oauthPlugin = new OauthPlugin(
                array(
                    'consumer_key'       => $this->consumerKey,
                    'consumer_secret'    => $this->consumerSecret,
                    'token'              => $rawToken['oauth_token'],
                    'token_secret'       => $rawToken['oauth_token_secret'],
                    'signature_method'   => 'RSA-SHA1',
                    'signature_callback' => function ($stringToSign, $key) use ($privateKey) {
                        if (!file_exists($privateKey)) {
                            throw new \InvalidArgumentException("Private key {$privateKey} does not exist");
                        }

                        $certificate = openssl_pkey_get_private('file://' . $privateKey);

                        $privateKeyId = openssl_get_privatekey($certificate);

                        $signature = null;

                        openssl_sign($stringToSign, $signature, $privateKeyId);
                        openssl_free_key($privateKeyId);

                        return $signature;
                    }
                )
            );

            $this->guzzle->addSubscriber($oauthPlugin);
        }

        return $this->guzzle;
    }
}
