<?php

namespace Mitake\Tests;

use Mitake\Client;
use PHPUnit\Framework\TestCase;

/**
 * Class ClientTest
 * @package Mitake
 */
class ClientTest extends TestCase
{
    public function testCreate()
    {
        $client = new Client('username', 'password', new \GuzzleHttp\Client());

        $this->assertEquals(Client::DEFAULT_USER_AGENT, $client->getUserAgent());
        $this->assertEquals('UserAgent', $client->setUserAgent('UserAgent')->getUserAgent());
        $this->assertEquals(Client::DEFAULT_BASE_URL, $client->getBaseURL());
        $this->assertEquals('BaseURL', $client->setBaseURL('BaseURL')->getBaseURL());
    }

    public function testBuildQuery()
    {
        $client = new Client('username', 'password', new \GuzzleHttp\Client());

        $this->assertEquals('?username=username&password=password', $client->buildQuery([]));
        $this->assertEquals(
            '?username=username&password=password&encoding=UTF8',
            $client->buildQuery(['encoding' => 'UTF8'])
        );
    }
}
