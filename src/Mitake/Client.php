<?php

namespace Mitake;

use Mitake\Exception\BadResponseException;
use GuzzleHttp\ClientInterface;
use GuzzleHttp\Psr7\Request;
use Psr\Http\Message\ResponseInterface;

/**
 * Class Client
 * @package Mitake
 */
class Client
{
    const LIBRARY_VERSION = "0.0.1";

    const DEFAULT_BASE_URL = "https://smexpress.mitake.com.tw:9601/";

    const DEFAULT_USER_AGENT = "mitake-php/" . self::LIBRARY_VERSION;

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
     * @var string
     */
    protected $baseURL;

    /**
     * Client constructor.
     *
     * @param string $username
     * @param string $password
     * @param ClientInterface $httpClient
     */
    public function __construct($username, $password, ClientInterface $httpClient)
    {
        $this->username = $username;
        $this->password = $password;
        $this->userAgent = self::DEFAULT_USER_AGENT;
        $this->baseURL = self::DEFAULT_BASE_URL;
        $this->httpClient = $httpClient;
    }

    /**
     * Create a new Mitake API client
     *
     * @param string $username
     * @param string $password
     * @param ClientInterface $httpClient
     * @return Client
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
     * @return string
     */
    public function getBaseURL()
    {
        return $this->baseURL;
    }

    /**
     * @param string $baseURL
     * @return $this
     */
    public function setBaseURL($baseURL)
    {
        $this->baseURL = $baseURL;

        return $this;
    }

    /**
     * Send an API request
     *
     * @param Request $request
     * @return ResponseInterface
     */
    public function send(Request $request)
    {
        $response = $this->httpClient->send($request);

        $this->checkErrorResponse($response);

        return $response;
    }

    /**
     * Create an API request
     *
     * @param string $method
     * @param string $url
     * @param string|null $contentType
     * @param string|null $body
     * @return Request
     */
    public function newRequest($method, $url, $contentType = null, $body = null)
    {
        $headers = [
            'User-Agent' => $this->userAgent,
        ];
        if (!is_null($contentType)) {
            $headers['Content-Type'] = $contentType;
        }

        return new Request(
            $method,
            $this->baseURL . $url,
            $headers,
            $body
        );
    }

    /**
     * Return the query string with authentication parameters
     *
     * @param array $params
     * @return string
     */
    public function buildQuery(array $params = [])
    {
        $default = [
            'username' => $this->username,
            'password' => $this->password,
        ];

        return '?' . http_build_query(array_merge($default, $params));
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
}
