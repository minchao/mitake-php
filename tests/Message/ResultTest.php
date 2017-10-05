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
        $result = (new Result())
            ->setMsgid('1234567890')
            ->setStatuscode(new StatusCode('4'));

        $this->assertEquals('1234567890', $result->getMsgid());
        $this->assertEquals(new StatusCode('4'), $result->getStatuscode());

        return $result;
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
