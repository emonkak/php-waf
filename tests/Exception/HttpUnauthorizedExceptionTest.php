<?php

namespace Emonkak\Framework\Tests\Exception;

use Emonkak\Framework\Exception\HttpUnauthorizedException;

class HttpUnauthorizedExceptionTest extends \PHPUnit_Framework_TestCase
{
    public function testGetStatusCode()
    {
        $exception = new HttpUnauthorizedException('challenge');
        $this->assertSame(401, $exception->getStatusCode());
    }

    public function testGetHeaders()
    {
        $exception = new HttpUnauthorizedException('Basic realm="test"');
        $this->assertSame(['WWW-Authenticate' => 'Basic realm="test"'], $exception->getHeaders());
    }

    public function testGetMessage()
    {
        $exception = new HttpUnauthorizedException('challenge', 'unauthorized');
        $this->assertSame('unauthorized', $exception->getMessage());
    }

    public function testGetPrevious()
    {
        $previous = new \Exception();
        $exception = new HttpUnauthorizedException('challenge', 'unauthorized', $previous);
        $this->assertSame($previous, $exception->getPrevious());
    }
}
