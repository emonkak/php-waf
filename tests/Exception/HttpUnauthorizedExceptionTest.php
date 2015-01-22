<?php

namespace Emonkak\Framework\Tests\Exception;

use Emonkak\Framework\Exception\HttpUnauthorizedException;
use Symfony\Component\HttpFoundation\Response;

class HttpUnauthorizedExceptionTest extends \PHPUnit_Framework_TestCase
{
    public function testGetStatusCode()
    {
        $exception = new HttpUnauthorizedException('realm');
        $this->assertSame(Response::HTTP_UNAUTHORIZED, $exception->getStatusCode());
    }

    public function testGetHeaders()
    {
        $exception = new HttpUnauthorizedException('realm');
        $this->assertSame(['WWW-Authenticate' => 'Basic realm="realm"'], $exception->getHeaders());
    }

    public function testGetMessage()
    {
        $exception = new HttpUnauthorizedException('realm', 'unauthorized');
        $this->assertSame('unauthorized', $exception->getMessage());
    }

    public function testGetPrevious()
    {
        $previous = new \Exception();
        $exception = new HttpUnauthorizedException('realm', 'unauthorized', $previous);
        $this->assertSame($previous, $exception->getPrevious());
    }
}
