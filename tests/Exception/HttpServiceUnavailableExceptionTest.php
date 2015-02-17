<?php

namespace Emonkak\Waf\Tests\Controller;

use Emonkak\Waf\Exception\HttpServiceUnavailableException;

class HttpServiceUnavailableExceptionTest extends \PHPUnit_Framework_TestCase
{
    public function testGetStatusCode()
    {
        $exception = new HttpServiceUnavailableException();
        $this->assertSame(503, $exception->getStatusCode());
    }

    public function testGetHeaders()
    {
        $exception = new HttpServiceUnavailableException();
        $this->assertEmpty($exception->getHeaders());
    }

    public function testGetMessage()
    {
        $exception = new HttpServiceUnavailableException('service unavailable');
        $this->assertSame('service unavailable', $exception->getMessage());
    }

    public function testGetPrevious()
    {
        $previous = new \Exception();
        $exception = new HttpServiceUnavailableException('service unavailable', $previous);
        $this->assertSame($previous, $exception->getPrevious());
    }
}
