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
        $status = (new Status())
            ->setMsgid('1234567890')
            ->setStatuscode(new StatusCode('4'))
            ->setStatustime('20171001112328');
        $resp = (new StatusResponse())
            ->addStatus($status);

        $this->assertEquals([$status], $resp->getStatuses());

        $resp->setStatuses([$status]);

        $this->assertEquals([$status], $resp->getStatuses());

        return $resp;
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
