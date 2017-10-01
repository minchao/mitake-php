<?php

namespace Mitake\Tests\Message;

use Mitake\Message\Receipt;
use Mitake\Message\StatusCode;
use PHPUnit\Framework\TestCase;

/**
 * Class ReceiptTest
 * @package Mitake\Tests\Message
 */
class ReceiptTest extends TestCase
{
    public function testToArray()
    {
        $receipt = (new Receipt())
            ->setMsgid('1234567890')
            ->setDstaddr('0987654321')
            ->setDlvtime('20171001112328')
            ->setDonetime('20171001112345')
            ->setStatuscode(new StatusCode('0'))
            ->setStatusstr('DELIVRD')
            ->setStatusFlag('4');

        $expected = [
            'msgid' => '1234567890',
            'dstaddr' => '0987654321',
            'dlvtime' => '20171001112328',
            'donetime' => '20171001112345',
            'statuscode' => '0',
            'statusstr' => 'DELIVRD',
            'StatusFlag' => '4',
        ];

        $this->assertEquals($expected, $receipt->toArray());
    }
}
