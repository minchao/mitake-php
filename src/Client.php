<?php

namespace Mitake;

use GuzzleHttp\Psr7\Query;
use GuzzleHttp\Psr7\Utils;
use Mitake\Exception\BadResponseException;
use GuzzleHttp\ClientInterface;
use GuzzleHttp\Client as GuzzleHttpClient;
use GuzzleHttp\Psr7\Uri;
use GuzzleHttp\Psr7\Request;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\UriInterface;

/**
 * Class Client
 * @package Mitake
 *
 * @method Message\Response send(Message\Message $message)
 * @method Message\Response sendBatch(array $messages)
 * @method Message\Response sendLongMessage(Message\LongMessage $message)
 * @method Message\Response sendLongMessageBatch(array $messages)
 * @method integer queryAccountPoint()
 * @method Message\StatusResponse queryMessageStatus(array $ids)
 * @method Message\StatusResponse cancelMessageStatus(array $ids)
 */
class Client
{
    const LIBRARY_VERSION = '0.4.0';

    const DEFAULT_BASE_URL = 'https://smsapi.mitake.com.tw';

    const DEFAULT_HTTP_BASE_URL = 'http://smsapi.mitake.com.tw';

    const DEFAULT_LONG_MESSAGE_BASE_URL = 'https://smsapi.mitake.com.tw';

    const DEFAULT_LONG_MESSAGE_HTTP_BASE_URL = 'http://smsapi.mitake.com.tw';

    const DEFAULT_USER_AGENT = 'mitake-php/' . self::LIBRARY_VERSION;

    /**
     * @var ClientInterface
     */
    protected $httpClient;

    /**
     * @var string
     */
    protected $username;

    /**
     * @var string
     */
    protected $password;

    /**
     * @var string
     */
    protected $userAgent;

    /**
     * @var UriInterface
     */
    protected $baseURL;

    /**
     * @var UriInterface
     */
    protected $longMessageBaseURL;

    /**
     * @var API
     */
    protected $api;

    /**
     * Client constructor.
     *
     * @param string $username
     * @param string $password
     * @param ClientInterface|null $httpClient
     */
    public function __construct($username, $password, ClientInterface $httpClient = null)
    {
        $this->username = $username;
        $this->password = $password;
        $this->userAgent = self::DEFAULT_USER_AGENT;
        $this->baseURL = new Uri(self::DEFAULT_BASE_URL);
        $this->longMessageBaseURL = new Uri(self::DEFAULT_LONG_MESSAGE_BASE_URL);
        $this->httpClient = $httpClient ?: new GuzzleHttpClient();
        $this->api = new API($this);
    }

    /**
     * @param string $method
     * @param array $arguments
     * @return mixed
     */
    public function __call($method, $arguments)
    {
        if (!method_exists($this->api, $method)) {
            throw new \BadMethodCallException(sprintf('Method "%s" not found', $method));
        }

        return $this->getAPI()->$method(...$arguments);
    }

    /**
     * Create a new Mitake API client
     *
     * @param string $username
     * @param string $password
     * @param ClientInterface $httpClient
     * @return Client
     * @deprecated
     */
    public static function create($username, $password, ClientInterface $httpClient)
    {
        return new self($username, $password, $httpClient);
    }

    /**
     * @return string
     */
    public function getUserAgent()
    {
        return $this->userAgent;
    }

    /**
     * @param string $userAgent
     * @return $this
     */
    public function setUserAgent($userAgent)
    {
        $this->userAgent = $userAgent;

        return $this;
    }

    /**
     * @return UriInterface
     */
    public function getBaseURL()
    {
        return $this->baseURL;
    }

    /**
     * @param UriInterface $baseURL
     * @return $this
     */
    public function setBaseURL(UriInterface $baseURL)
    {
        $this->baseURL = $baseURL;

        return $this;
    }

    /**
     * @return UriInterface
     */
    public function getLongMessageBaseURL()
    {
        return $this->longMessageBaseURL;
    }

    /**
     * @param UriInterface $longMessageBaseURL
     * @return $this
     */
    public function setLongMessageBaseURL(UriInterface $longMessageBaseURL)
    {
        $this->longMessageBaseURL = $longMessageBaseURL;

        return $this;
    }

    /**
     * @param bool $enable
     * @return $this
     */
    public function useSecureBaseURL($enable = true)
    {
        if ($enable) {
            $this->baseURL = new Uri(self::DEFAULT_BASE_URL);
            $this->longMessageBaseURL = new Uri(self::DEFAULT_LONG_MESSAGE_BASE_URL);

            return $this;
        }

        $this->baseURL = new Uri(self::DEFAULT_HTTP_BASE_URL);
        $this->longMessageBaseURL = new Uri(self::DEFAULT_LONG_MESSAGE_HTTP_BASE_URL);

        return $this;
    }

    /**
     * Send an API request
     *
     * @param Request $request
     * @return ResponseInterface
     * @throws BadResponseException
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function sendRequest(Request $request)
    {
        $response = $this->httpClient->send($request);

        $this->checkErrorResponse($response);

        return $response;
    }

    /**
     * Create an API request
     *
     * @param string $method
     * @param string|UriInterface $uri
     * @param string|null $contentType
     * @param string|null $body
     * @return Request
     */
    public function newRequest($method, $uri, $contentType = null, $body = null)
    {
        // TODO $uri whitelist (trusted domain)

        $headers = [
            'User-Agent' => $this->userAgent,
        ];
        if (!is_null($contentType)) {
            $headers['Content-Type'] = $contentType;
        }

        return new Request(
            $method,
            $uri,
            $headers,
            $body
        );
    }

    /**
     * Return the uri with authentication parameters and query string
     *
     * @param string|UriInterface $uri
     * @param array $params Query string parameters
     *
     * @return UriInterface
     */
    public function buildUriWithQuery($uri, array $params = [])
    {
        $uri = Utils::uriFor($uri);

        if (!Uri::isAbsolute($uri)) {
            $uri = $uri->withScheme($this->baseURL->getScheme())
                ->withUserInfo($this->baseURL->getUserInfo())
                ->withHost($this->baseURL->getHost())
                ->withPort($this->baseURL->getPort());
        }

        $default = [
            'username' => $this->username,
            'password' => $this->password,
        ];

        return $uri->withQuery(Query::build(array_merge($default, $params)));
    }

    /**
     * Check the API response for errors
     *
     * @param ResponseInterface $response
     * @throws BadResponseException
     */
    protected function checkErrorResponse(ResponseInterface $response)
    {
        $statusCode = $response->getStatusCode();
        if (200 <= $statusCode && $statusCode < 299) {
            if (!$response->getBody()->getSize()) {
                throw new BadResponseException('unexpected empty body');
            }

            return;
        }

        // Mitake API always return status code 200
        throw new BadResponseException(sprintf('unexpected status code: %d', $statusCode));
    }

    /**
     * @return API
     */
    public function getAPI()
    {
        return $this->api;
    }
}
