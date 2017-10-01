<?php

namespace Mitake\Tests\Message;

use Mitake\Exception\InvalidArgumentException;
use Mitake\Message\StatusCode;
use PHPUnit\Framework\TestCase;

/**
 * Class StatusCodeTest
 * @package Mitake\Tests\Message
 */
class StatusCodeTest extends TestCase
{
    public function testConstruct()
    {
        $code = new StatusCode('4');

        $this->assertEquals('4', $code->__toString());
        $this->assertEquals('已送達手機', $code->message());
    }

    public function testConstructWithException()
    {
        $this->expectException(InvalidArgumentException::class);

        new StatusCode('E');
    }
}
