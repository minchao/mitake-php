<?php

namespace Mitake\Tests\Message;

use Mitake\Message\Status;
use Mitake\Message\StatusCode;
use Mitake\Message\StatusResponse;
use PHPUnit\Framework\TestCase;

/**
 * Class StatusResponseTest
 * @package Mitake\Tests\Message
 */
class StatusResponseTest extends TestCase
{
    /**
     * @return StatusResponse
     */
    public function testConstruct()
    {
        $s = (new Status())
            ->setMsgid('1234567890')
            ->setStatuscode(new StatusCode('4'))
            ->setStatustime('20171001112328');
        $r = (new StatusResponse())
            ->addStatus($s);

        $this->assertEquals([$s], $r->getStatuses());

        return $r;
    }

    /**
     * @depends testConstruct
     * @param StatusResponse $obj
     */
    public function testToArray($obj)
    {
        $expected = [
            'statuses' => [
                [
                    'msgid' => '1234567890',
                    'statuscode' => '4',
                    'statustime' => '20171001112328',
                ],
            ],
        ];

        $this->assertEquals($expected, $obj->toArray());
    }
}
