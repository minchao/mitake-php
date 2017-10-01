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
    /**
     * @return Receipt
     */
    public function testConstruct()
    {
        $receipt = (new Receipt())
            ->setMsgid('1234567890')
            ->setDstaddr('0987654321')
            ->setDlvtime('20171001112328')
            ->setDonetime('20171001112345')
            ->setStatuscode(new StatusCode('0'))
            ->setStatusstr('DELIVRD')
            ->setStatusFlag('4');

        $this->assertEquals('1234567890', $receipt->getMsgid());
        $this->assertEquals('0987654321', $receipt->getDstaddr());
        $this->assertEquals('20171001112328', $receipt->getDlvtime());
        $this->assertEquals('20171001112345', $receipt->getDonetime());
        $this->assertEquals(new StatusCode('0'), $receipt->getStatuscode());
        $this->assertEquals('DELIVRD', $receipt->getStatusstr());
        $this->assertEquals('4', $receipt->getStatusFlag());

        return $receipt;
    }

    /**
     * @depends testConstruct
     * @param $obj
     */
    public function testToArray($obj)
    {
        $expected = [
            'msgid' => '1234567890',
            'dstaddr' => '0987654321',
            'dlvtime' => '20171001112328',
            'donetime' => '20171001112345',
            'statuscode' => '0',
            'statusstr' => 'DELIVRD',
            'StatusFlag' => '4',
        ];

        $this->assertEquals($expected, $obj->toArray());
    }
}
