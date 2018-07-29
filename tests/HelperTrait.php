<?php

namespace Mitake\Tests;

use GuzzleHttp\Client as GuzzleHttpClient;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\Middleware;
use GuzzleHttp\Psr7\Response;
use Mitake\Client;

trait HelperTrait
{
    /**
     * @param Response $response
     * @param array|\ArrayAccess $history
     * @return GuzzleHttpClient
     */
    public function createMockHttpClient(Response $response, &$history = [])
    {
        $mock = new MockHandler([
            $response,
        ]);

        $handler = HandlerStack::create($mock);
        $handler->push(Middleware::history($history));

        return new GuzzleHttpClient(['handler' => $handler]);
    }

    public function createClient(GuzzleHttpClient $httpClient = null)
    {
        return new Client('username', 'password', $httpClient);
    }
}
