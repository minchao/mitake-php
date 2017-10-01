<?php

namespace Mitake\Tests\Message;

use Mitake\Message\Message;
use PHPUnit\Framework\TestCase;

/**
 * Class MessageTest
 * @package Mitake\Tests\Message
 */
class MessageTest extends TestCase
{
    /**
     * @return Message
     */
    public function testConstruct()
    {
        $smBody = 'Hello,' . chr(6) . '世界';

        $message = (new Message())
            ->setDstaddr('0987654321')
            ->setSmbody($smBody)
            ->setDlvtime('60')
            ->setVldtime('120')
            ->setResponse('https://example.com');

        $this->assertEquals('0987654321', $message->getDstaddr());
        $this->assertEquals($smBody, $message->getSmbody());
        $this->assertEquals('60', $message->getDlvtime());
        $this->assertEquals('120', $message->getVldtime());
        $this->assertEquals('https://example.com', $message->getResponse());

        return $message;
    }

    /**
     * @depends testConstruct
     * @param Message $obj
     */
    public function testToINI($obj)
    {
        $smBody = 'Hello,' . chr(6) . '世界';

        $expected = <<<EOT
dstaddr=0987654321
smbody=$smBody
dlvtime=60
vldtime=120
response=https://example.com

EOT;

        $this->assertEquals($expected, $obj->toINI());
    }
}
