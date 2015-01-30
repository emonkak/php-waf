<?php

namespace Emonkak\Framework\Middleware;

use Emonkak\Framework\Exception\HttpException;
use Emonkak\Framework\Middleware\ExceptionThrowerMiddleware;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class ExceptionThrowerMiddlewareTest extends \PHPUnit_Framework_TestCase
{
    public function testHandleResponse()
    {
        $request = new Request();
        $response = new Response();

        $kernel = $this->getMock('Emonkak\Framework\KernelInterface');
        $kernel
            ->expects($this->once())
            ->method('handleRequest')
            ->with($this->identicalTo($request))
            ->willReturn($response);

        $kernel = new ExceptionThrowerMiddleware($kernel);

        $this->assertSame($response, $kernel->handleRequest($request));
    }

    /**
     * @expectedException Emonkak\Framework\Exception\HttpException
     */
    public function testHandleException()
    {
        $request = new Request();
        $exception = new HttpException(404);

        $kernel = $this->getMock('Emonkak\Framework\KernelInterface');
        $kernel = new ExceptionThrowerMiddleware($kernel);

        try {
            $kernel->handleException($request, $exception);
        } catch (HttpException $e) {
            $this->assertSame($exception, $e);
            throw $e;
        }
    }

    public function testAllowStatusCode()
    {
        $request = new Request();
        $response = new Response();
        $exception = new HttpException(302);

        $kernel = $this->getMock('Emonkak\Framework\KernelInterface');
        $kernel
            ->expects($this->once())
            ->method('handleException')
            ->with($this->identicalTo($request), $this->identicalTo($exception))
            ->willReturn($response);

        $kernel = new ExceptionThrowerMiddleware($kernel);

        $this->assertSame($kernel, $kernel->allowStatusCode(302));
        $this->assertSame($response, $kernel->handleException($request, $exception));
    }
}
