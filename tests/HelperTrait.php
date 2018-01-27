<?php

namespace Mitake\Tests;

use GuzzleHttp\Client as GuzzleHttpClient;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\Psr7\Response;
use Mitake\Client;

trait HelperTrait
{
    /**
     * @param Response $response
     * @return GuzzleHttpClient
     */
    public function createMockHttpClient(Response $response)
    {
        $mock = new MockHandler([
            $response,
        ]);

        $handler = HandlerStack::create($mock);
        return new GuzzleHttpClient(['handler' => $handler]);
    }

    public function createClient(GuzzleHttpClient $httpClient = null)
    {
        return new Client('username', 'password', $httpClient);
    }
}
