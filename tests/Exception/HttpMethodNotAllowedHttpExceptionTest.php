<?php

namespace Emonkak\Waf\Tests\Controller;

use Emonkak\Waf\Exception\HttpMethodNotAllowedException;

class HttpMethodNotAllowedExceptionTest extends \PHPUnit_Framework_TestCase
{
    public function testGetStatusCode()
    {
        $exception = new HttpMethodNotAllowedException(['GET', 'POST']);
        $this->assertSame(405, $exception->getStatusCode());
    }

    public function testGetHeaders()
    {
        $exception = new HttpMethodNotAllowedException(['GET', 'POST']);
        $this->assertSame(['Allow' => 'GET, POST'], $exception->getHeaders());
    }

    public function testGetMessage()
    {
        $exception = new HttpMethodNotAllowedException(['GET', 'POST'], 'method not allowed');
        $this->assertSame('method not allowed', $exception->getMessage());
    }

    public function testGetPrevious()
    {
        $previous = new \Exception();
        $exception = new HttpMethodNotAllowedException(['GET', 'POST'], 'method not allowed', $previous);
        $this->assertSame($previous, $exception->getPrevious());
    }
}
