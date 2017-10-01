<?php

namespace Mitake\Tests\Message;

use Mitake\Message\Result;
use Mitake\Message\StatusCode;
use PHPUnit\Framework\TestCase;

class ResultTest extends TestCase
{
    /**
     * @return Result
     */
    public function testConstruct()
    {
        $r = (new Result())
            ->setMsgid('1234567890')
            ->setStatuscode(new StatusCode('4'));

        $this->assertEquals('1234567890', $r->getMsgid());
        $this->assertEquals(new StatusCode('4'), $r->getStatuscode());

        return $r;
    }

    /**
     * @depends testConstruct
     * @param Result $obj
     */
    public function testToArray($obj)
    {
        $expected = [
            'msgid' => '1234567890',
            'statuscode' => '4',
        ];

        $this->assertEquals($expected, $obj->toArray());
    }
}
