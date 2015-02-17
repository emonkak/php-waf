<?php

namespace Emonkak\Waf\Tests\Controller;

use Emonkak\Waf\Exception\HttpForbiddenException;

class HttpForbiddenExceptionTest extends \PHPUnit_Framework_TestCase
{
    public function testGetStatusCode()
    {
        $exception = new HttpForbiddenException();
        $this->assertSame(403, $exception->getStatusCode());
    }

    public function testGetHeaders()
    {
        $exception = new HttpForbiddenException();
        $this->assertEmpty($exception->getHeaders());
    }

    public function testGetMessage()
    {
        $exception = new HttpForbiddenException('forbidden');
        $this->assertSame('forbidden', $exception->getMessage());
    }

    public function testGetPrevious()
    {
        $previous = new \Exception();
        $exception = new HttpForbiddenException('forbidden', $previous);
        $this->assertSame($previous, $exception->getPrevious());
    }
}
