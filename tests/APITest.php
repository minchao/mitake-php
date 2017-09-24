<?php

namespace Mitake\Tests;

use Mitake\Client;
use Mitake\Message;
use Mitake\Exception\InvalidArgumentException;
use GuzzleHttp\Psr7\Response;
use PHPUnit\Framework\TestCase;

/**
 * Class APITest
 * @package Mitake\Tests
 */
class APITest extends TestCase
{
    use HelperTrait;

    public function testSendBatch()
    {
        $body = '[0]
msgid=1010079522
statuscode=1
[1]
msgid=1010079523
statuscode=4
AccountPoint=98';
        $resp = new Response(
            200,
            [
                'Content-Type' => 'text/plain',
            ],
            $body
        );

        $httpClient = $this->createMockHttpClient($resp);

        $client = Client::create('', '', $httpClient);
        $actual = $client->getAPI()->sendBatch([
            (new Message\Message())
                ->setDstaddr('0987654321')
                ->setSmbody('Test 1'),
            (new Message\Message())
                ->setDstaddr('0987654322')
                ->setSmbody('Test 2'),
        ]);

        $expected = (new Message\Response())
            ->addResult(
                (new Message\Result())
                    ->setMsgid('1010079522')
                    ->setStatuscode(new Message\StatusCode('1'))
            )
            ->addResult(
                (new Message\Result())
                    ->setMsgid('1010079523')
                    ->setStatuscode(new Message\StatusCode('4'))
            )
            ->setAccountPoint(98);

        $this->assertEquals($expected, $actual);
    }

    public function testSendBatchWithInvalidArgumentException()
    {
        $this->expectException(InvalidArgumentException::class);

        $httpClient = $this->createMockHttpClient(new Response(200));

        $client = Client::create('', '', $httpClient);
        $client->getAPI()->sendBatch(['TEST']);
    }

    public function testSend()
    {
        $body = '[0]
msgid=1010079522
statuscode=1
AccountPoint=99';
        $resp = new Response(
            200,
            [
                'Content-Type' => 'text/plain',
            ],
            $body
        );

        $httpClient = $this->createMockHttpClient($resp);

        $client = Client::create('', '', $httpClient);
        $actual = $client->getAPI()->send(
            (new Message\Message())
                ->setDstaddr('0987654321')
                ->setSmbody('Test 1')
        );

        $expected = (new Message\Response())
            ->addResult(
                (new Message\Result())
                    ->setMsgid('1010079522')
                    ->setStatuscode(new Message\StatusCode('1'))
            )
            ->setAccountPoint(99);

        $this->assertEquals($expected, $actual);
    }

    public function testQueryAccountPoint()
    {
        $resp = new Response(
            200,
            [
                'Content-Type' => 'text/plain',
            ],
            'AccountPoint=100'
        );

        $httpClient = $this->createMockHttpClient($resp);

        $client = Client::create('', '', $httpClient);
        $actual = $client->getAPI()->queryAccountPoint();

        $this->assertEquals(100, $actual);
    }

    public function testQueryMessageStatus()
    {
        $body = '1010079522	1	20170101010010
1010079523	4	20170101010011';
        $resp = new Response(
            200,
            [
                'Content-Type' => 'text/plain',
            ],
            $body
        );

        $httpClient = $this->createMockHttpClient($resp);

        $client = Client::create('', '', $httpClient);
        $actual = $client->getAPI()->queryMessageStatus(['1010079522', '1010079523']);

        $expected = (new Message\StatusResponse())
            ->addStatus(
                (new Message\Status())
                    ->setMsgid('1010079522')
                    ->setStatuscode(new Message\StatusCode('1'))
                    ->setStatustime('20170101010010')
            )
            ->addStatus(
                (new Message\Status())
                    ->setMsgid('1010079523')
                    ->setStatuscode(new Message\StatusCode('4'))
                    ->setStatustime('20170101010011')
            );

        $this->assertEquals($expected, $actual);
    }

    public function testCancelMessageStatus()
    {
        $body = '1010079522=8
1010079523=9';
        $resp = new Response(
            200,
            [
                'Content-Type' => 'text/plain',
            ],
            $body
        );

        $httpClient = $this->createMockHttpClient($resp);

        $client = Client::create('', '', $httpClient);
        $actual = $client->getAPI()->cancelMessageStatus(['1010079522', '1010079523']);

        $expected = (new Message\StatusResponse())
            ->addStatus(
                (new Message\Status())
                    ->setMsgid('1010079522')
                    ->setStatuscode(new Message\StatusCode('8'))
            )
            ->addStatus(
                (new Message\Status())
                    ->setMsgid('1010079523')
                    ->setStatuscode(new Message\StatusCode('9'))
            );

        $this->assertEquals($expected, $actual);
    }
}
