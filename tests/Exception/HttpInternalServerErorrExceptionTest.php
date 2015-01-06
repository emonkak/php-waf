<?php

namespace Emonkak\Framework\Tests\Controller;

use Emonkak\Framework\Exception\HttpInternalServerErrorException;
use Symfony\Component\HttpFoundation\Response;

class HttpInternalServerErrorExceptionTest extends \PHPUnit_Framework_TestCase
{
    public function testGetStatusCode()
    {
        $exception = new HttpInternalServerErrorException();
        $this->assertSame(Response::HTTP_INTERNAL_SERVER_ERROR, $exception->getStatusCode());
    }

    public function testGetHeaders()
    {
        $exception = new HttpInternalServerErrorException();
        $this->assertEmpty($exception->getHeaders());
    }

    public function testGetMessage()
    {
        $exception = new HttpInternalServerErrorException('internel server error');
        $this->assertSame('internel server error', $exception->getMessage());
    }

    public function testGetPrevious()
    {
        $previous = new \Exception();
        $exception = new HttpInternalServerErrorException('internel server error', $previous);
        $this->assertSame($previous, $exception->getPrevious());
    }
}
