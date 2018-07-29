<?php

namespace Mitake\Tests\Message;

use Mitake\Message\LongMessage;
use PHPUnit\Framework\TestCase;

class LongMessageTest extends TestCase
{
    /**
     * @return LongMessage
     */
    public function testConstruct()
    {
        $smBody = 'Hello,' . chr(6) . '世界';

        $message = (new LongMessage())
            ->setId('001')
            ->setDstaddr('0987654321')
            ->setDlvtime('60')
            ->setVldtime('120')
            ->setDestname('John')
            ->setResponse('https://example.com/callback')
            ->setSmbody($smBody);

        $this->assertEquals('001', $message->getId());
        $this->assertEquals('0987654321', $message->getDstaddr());
        $this->assertEquals('60', $message->getDlvtime());
        $this->assertEquals('120', $message->getVldtime());
        $this->assertEquals('John', $message->getDestname());
        $this->assertEquals('https://example.com/callback', $message->getResponse());
        $this->assertEquals($smBody, $message->getSmbody());

        return $message;
    }

    /**
     * @depends testConstruct
     * @param LongMessage $obj
     */
    public function testToINI($obj)
    {
        $smBody = 'Hello,' . chr(6) . '世界';

        $expected = '001$$0987654321$$60$$120$$John$$https://example.com/callback$$' . "{$smBody}\n";

        $this->assertEquals($expected, $obj->toINI());
    }
}
