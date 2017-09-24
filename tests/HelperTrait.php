<?php

namespace Mitake\Tests;

use GuzzleHttp\Client;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\Psr7\Response;

trait HelperTrait
{
    /**
     * @param Response $response
     * @return Client
     */
    public function createMockHttpClient(Response $response)
    {
        $mock = new MockHandler([
            $response,
        ]);

        $handler = HandlerStack::create($mock);
        return new Client(['handler' => $handler]);
    }
}
