<?php

namespace Emonkak\Framework\Tests\Controller;

use Emonkak\Framework\Exception\HttpBadRequestException;

class HttpBadRequestExceptionTest extends \PHPUnit_Framework_TestCase
{
    public function testGetStatusCode()
    {
        $exception = new HttpBadRequestException();
        $this->assertSame(400, $exception->getStatusCode());
    }

    public function testGetHeaders()
    {
        $exception = new HttpBadRequestException();
        $this->assertEmpty($exception->getHeaders());
    }

    public function testGetMessage()
    {
        $exception = new HttpBadRequestException('bad request');
        $this->assertSame('bad request', $exception->getMessage());
    }

    public function testGetPrevious()
    {
        $previous = new \Exception();
        $exception = new HttpBadRequestException('bad request', $previous);
        $this->assertSame($previous, $exception->getPrevious());
    }
}
