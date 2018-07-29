<?php

namespace Mitake\Tests;

use Mitake\Client;
use Mitake\Exception\BadResponseException;
use GuzzleHttp\Psr7\Uri;
use GuzzleHttp\Psr7\Request;
use GuzzleHttp\Psr7\Response;
use PHPUnit\Framework\TestCase;

/**
 * Class ClientTest
 * @package Mitake
 */
class ClientTest extends TestCase
{
    use HelperTrait;

    public function testCreate()
    {
        $client = $this->createClient();

        $this->assertEquals(Client::DEFAULT_USER_AGENT, $client->getUserAgent());
        $this->assertEquals('UserAgent', $client->setUserAgent('UserAgent')->getUserAgent());
        $this->assertEquals(Client::DEFAULT_BASE_URL, $client->getBaseURL());
        $this->assertEquals(new Uri('BaseURL'), $client->setBaseURL(new Uri('BaseURL'))->getBaseURL());
        $this->assertEquals(
            new Uri('LongMessageBaseURL'),
            $client->setLongMessageBaseURL(new Uri('LongMessageBaseURL'))->getLongMessageBaseURL()
        );
    }

    public function testShouldThrowBadMethodCallExceptionWhenCallNotExistsMethodInClient()
    {
        $this->expectException(\BadMethodCallException::class);
        $this->expectExceptionMessage('Method "badMethod" not found');

        $client = $this->createClient();
        $client->badMethod();
    }

    public function testSendWithEmptyResponseBody()
    {
        $this->expectException(BadResponseException::class);
        $this->expectExceptionMessage('unexpected empty body');

        $httpClient = $this->createMockHttpClient(new Response(200));

        $client = $this->createClient($httpClient);
        $client->sendRequest(new Request('GET', '/'));
    }

    public function testSendWithUnexpectedStatusCode()
    {
        $this->expectException(BadResponseException::class);
        $this->expectExceptionMessage('unexpected status code');

        $httpClient = $this->createMockHttpClient(new Response(301));

        $client = $this->createClient($httpClient);
        $client->sendRequest(new Request('GET', '/'));
    }

    public function getBuildUriWithQueryCases()
    {
        return [
            [
                '',
                [],
                Client::DEFAULT_BASE_URL . '?username=username&password=password',
            ],
            [
                '',
                ['encoding' => 'UTF8'],
                Client::DEFAULT_BASE_URL . '?username=username&password=password&encoding=UTF8',
            ],
            [
                'path/sub',
                ['encoding' => 'UTF8'],
                Client::DEFAULT_BASE_URL . '/path/sub?username=username&password=password&encoding=UTF8',
            ],
            [
                Client::DEFAULT_BASE_URL . '/path/sub',
                ['encoding' => 'UTF8'],
                Client::DEFAULT_BASE_URL . '/path/sub?username=username&password=password&encoding=UTF8',
            ],
        ];
    }

    /**
     * @dataProvider getBuildUriWithQueryCases
     * @param string $uri
     * @param array $queryParams
     * @param string $expected
     */
    public function testBuildUriWithQuery($uri, $queryParams, $expected)
    {
        $client = $this->createClient();

        $actual = $client->buildUriWithQuery($uri, $queryParams)->__toString();

        $this->assertEquals($expected, $actual);
    }
}
