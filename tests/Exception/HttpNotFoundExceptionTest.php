<?php

namespace Emonkak\Waf\Tests\Controller;

use Emonkak\Waf\Exception\HttpNotFoundException;

class HttpNotFoundExceptionTest extends \PHPUnit_Framework_TestCase
{
    public function testGetStatusCode()
    {
        $exception = new HttpNotFoundException();
        $this->assertSame(404, $exception->getStatusCode());
    }

    public function testGetHeaders()
    {
        $exception = new HttpNotFoundException();
        $this->assertEmpty($exception->getHeaders());
    }

    public function testGetMessage()
    {
        $exception = new HttpNotFoundException('not found');
        $this->assertSame('not found', $exception->getMessage());
    }

    public function testGetPrevious()
    {
        $previous = new \Exception();
        $exception = new HttpNotFoundException('not found', $previous);
        $this->assertSame($previous, $exception->getPrevious());
    }
}
