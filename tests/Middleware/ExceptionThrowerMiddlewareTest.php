<?php

namespace Emonkak\Waf\Middleware;

use Emonkak\Waf\Exception\HttpException;
use Emonkak\Waf\Middleware\ExceptionThrowerMiddleware;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class ExceptionThrowerMiddlewareTest extends \PHPUnit_Framework_TestCase
{
    public function testHandleResponse()
    {
        $request = new Request();
        $response = new Response();

        $kernel = $this->getMock('Emonkak\Waf\KernelInterface');
        $kernel
            ->expects($this->once())
            ->method('handleRequest')
            ->with($this->identicalTo($request))
            ->willReturn($response);

        $kernel = new ExceptionThrowerMiddleware($kernel);

        $this->assertSame($response, $kernel->handleRequest($request));
    }

    /**
     * @dataProvider provideHandleException
     */
    public function testHandleException(HttpException $exception, $allow)
    {
        $request = new Request();
        $response = new Response();

        $kernel = $this->getMock('Emonkak\Waf\KernelInterface');
        $kernel
            ->expects($this->once())
            ->method('handleException')
            ->with($this->identicalTo($request), $this->identicalTo($exception))
            ->willReturn($response);
        $kernel = new ExceptionThrowerMiddleware($kernel);

        $allow($kernel);

        $this->assertSame($response, $kernel->handleException($request, $exception));
    }

    public function provideHandleException()
    {
        return [
            [new HttpException(302), function($kernel) { return $kernel->allowRedirection(); }],
            [new HttpException(400), function($kernel) { return $kernel->allowClientError(); }],
            [new HttpException(500), function($kernel) { return $kernel->allowServerError(); }],
        ];
    }

    /**
     * @dataProvider provideHandleExceptionThrowsHttpException
     * @expectedException Emonkak\Waf\Exception\HttpException
     */
    public function testHandleExceptionThrowsHttpException(HttpException $exception, $allow)
    {
        $request = new Request();

        $kernel = $this->getMock('Emonkak\Waf\KernelInterface');
        $kernel = new ExceptionThrowerMiddleware($kernel);

        $allow($kernel);

        try {
            $kernel->handleException($request, $exception);
        } catch (HttpException $e) {
            $this->assertSame($exception, $e);
            throw $e;
        }
    }

    public function provideHandleExceptionThrowsHttpException()
    {
        return [
            [new HttpException(200), function($kernel) {}],
            [new HttpException(200), function($kernel) { return $kernel->allowRedirection(); }],
            [new HttpException(200), function($kernel) { return $kernel->allowClientError(); }],
            [new HttpException(200), function($kernel) { return $kernel->allowServerError(); }],
            [new HttpException(302), function($kernel) {}],
            [new HttpException(302), function($kernel) { return $kernel->allowClientError(); }],
            [new HttpException(302), function($kernel) { return $kernel->allowServerError(); }],
            [new HttpException(400), function($kernel) {}],
            [new HttpException(400), function($kernel) { return $kernel->allowRedirection(); }],
            [new HttpException(400), function($kernel) { return $kernel->allowServerError(); }],
            [new HttpException(500), function($kernel) {}],
            [new HttpException(500), function($kernel) { return $kernel->allowRedirection(); }],
            [new HttpException(500), function($kernel) { return $kernel->allowClientError(); }],
        ];
    }
}
