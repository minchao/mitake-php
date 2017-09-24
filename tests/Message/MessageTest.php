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
    public function testToINI()
    {
        $smBody = 'Hello,' . chr(6) . '世界';

        $message = (new Message())
            ->setDstaddr('0987654321')
            ->setSmbody($smBody)
            ->setDlvtime('60')
            ->setVldtime('120')
            ->setResponse('https://example.com');

        $expected = <<<EOT
dstaddr=0987654321
smbody=$smBody
dlvtime=60
vldtime=120
response=https://example.com

EOT;
        $actual = $message->toINI();

        $this->assertEquals($expected, $actual);
    }
}
