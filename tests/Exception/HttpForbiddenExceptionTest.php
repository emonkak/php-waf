<?php

namespace Emonkak\Framework\Tests\Controller;

use Emonkak\Framework\Exception\HttpForbiddenException;
use Symfony\Component\HttpFoundation\Response;

class HttpForbiddenExceptionTest extends \PHPUnit_Framework_TestCase
{
    public function testGetStatusCode()
    {
        $exception = new HttpForbiddenException();
        $this->assertSame(Response::HTTP_FORBIDDEN, $exception->getStatusCode());
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
