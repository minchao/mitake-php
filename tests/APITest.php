<?php

namespace Mitake\Tests;

use GuzzleHttp\Psr7\Response;
use GuzzleHttp\Psr7\Request;
use Mitake\Message;
use Mitake\Exception\InvalidArgumentException;
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

        $history = [];
        $httpClient = $this->createMockHttpClient($resp, $history);
        $client = $this->createClient($httpClient);

        $actual = $client->sendBatch([
            (new Message\Message())
                ->setDstaddr('0987654321')
                ->setSmbody('壓迫孕育反逆 / 反逆產生壓迫')
                ->setDlvtime('60')
                ->setVldtime('120')
                ->setResponse('https://example.com/callback'),
            (new Message\Message())
                ->setDstaddr('0987654322')
                ->setSmbody('須知反逆得到勝利時 / 社會纔能進步改革'),
        ]);

        $expectedRequestBody = '1$$0987654321$$60$$120$$$$https://example.com/callback$$壓迫孕育反逆 / 反逆產生壓迫
2$$0987654322$$$$$$$$$$須知反逆得到勝利時 / 社會纔能進步改革';

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

        /** @var Request $request */
        $request = $history[0]['request'];

        $this->assertEquals($expectedRequestBody, $request->getBody()->getContents());
        $this->assertEquals($expected, $actual);
    }

    public function testSendBatchWithInvalidArgumentException()
    {
        $this->expectException(InvalidArgumentException::class);

        $httpClient = $this->createMockHttpClient(new Response(200));
        $client = $this->createClient($httpClient);

        $client->sendBatch(['TEST']);
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
        $client = $this->createClient($httpClient);

        $actual = $client->send(
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

    public function testSendLongMessageBatch()
    {
        $body = '[1]
msgid=#0299ECC0A
statuscode=1
[2]
msgid=#0299ECD70
statuscode=4
AccountPoint=98';
        $resp = new Response(
            200,
            [
                'Content-Type' => 'text/plain',
            ],
            $body
        );

        $history = [];
        $httpClient = $this->createMockHttpClient($resp, $history);
        $client = $this->createClient($httpClient);

        $actual = $client->sendLongMessageBatch([
            (new Message\LongMessage())
                ->setId('1')
                ->setDstaddr('0987654321')
                ->setDlvtime('60')
                ->setVldtime('120')
                ->setDestname('John')
                ->setResponse('https://example.com/callback')
                ->setSmbody('世間未許權存在，勇士當為義鬥爭。'),
            (new Message\LongMessage())
                ->setId('2')
                ->setDstaddr('0987654322')
                ->setSmbody('美麗島上經 / 散播了無限種子 / 自由的花、平等的樹 / 專待我們熱血來 / 培養起。'),
        ]);

        $expectedRequestBody = '1$$0987654321$$60$$120$$John$$https://example.com/callback$$世間未許權存在，勇士當為義鬥爭。
2$$0987654322$$$$$$$$$$美麗島上經 / 散播了無限種子 / 自由的花、平等的樹 / 專待我們熱血來 / 培養起。';

        $expected = (new Message\Response())
            ->addResult(
                (new Message\Result())
                    ->setMsgid('#0299ECC0A')
                    ->setStatuscode(new Message\StatusCode('1'))
            )
            ->addResult(
                (new Message\Result())
                    ->setMsgid('#0299ECD70')
                    ->setStatuscode(new Message\StatusCode('4'))
            )
            ->setAccountPoint(98);

        /** @var Request $request */
        $request = $history[0]['request'];

        $this->assertEquals($expectedRequestBody, $request->getBody()->getContents());
        $this->assertEquals($expected, $actual);
    }

    public function testSendLongMessage()
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
        $client = $this->createClient($httpClient);

        $actual = $client->sendLongMessage(
            (new Message\LongMessage())
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

    public function testSendLongMessageBatchWithInvalidArgumentException()
    {
        $this->expectException(InvalidArgumentException::class);

        $httpClient = $this->createMockHttpClient(new Response(200));
        $client = $this->createClient($httpClient);

        $client->sendLongMessageBatch([new Message\Message()]);
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
        $client = $this->createClient($httpClient);

        $actual = $client->queryAccountPoint();

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
        $client = $this->createClient($httpClient);

        $actual = $client->queryMessageStatus(['1010079522', '1010079523']);

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
        $client = $this->createClient($httpClient);

        $actual = $client->cancelMessageStatus(['1010079522', '1010079523']);

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
