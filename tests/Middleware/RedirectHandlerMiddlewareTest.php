<?php

namespace Emonkak\Framework\Tests\Middleware;

use Emonkak\Framework\Exception\HttpBadRequestException;
use Emonkak\Framework\Exception\HttpException;
use Emonkak\Framework\Exception\HttpForbiddenException;
use Emonkak\Framework\Exception\HttpInternalServerErrorException;
use Emonkak\Framework\Exception\HttpNotFoundException;
use Emonkak\Framework\Exception\HttpRedirectException;
use Emonkak\Framework\Exception\HttpServiceUnavailableException;
use Emonkak\Framework\Middleware\RedirectHandlerMiddleware;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class RedirectHandlerMiddlewareTest extends \PHPUnit_Framework_TestCase 
{
    public function testHandleRequest()
    {
        $request = new Request();
        $response = new Response();

        $kernel = $this->getMock('Emonkak\Framework\KernelInterface');
        $kernel
            ->expects($this->once())
            ->method('handleRequest')
            ->with($this->identicalTo($request))
            ->willReturn($response);

        $middleware = new RedirectHandlerMiddleware($kernel);
        $this->assertSame($response, $middleware->handleRequest($request));
    }

    /**
     * @dataProvider provideHandleRedirectException
     */
    public function testHandleRedirectException(HttpException $exception, $expectedStatusCode, $expectedLocation)
    {
        $request = new Request();

        $kernel = $this->getMock('Emonkak\Framework\KernelInterface');
        $kernel
            ->expects($this->never())
            ->method('handleException');

        $middleware = new RedirectHandlerMiddleware($kernel);
        $response = $middleware->handleException($request, $exception);

        $this->assertInstanceOf('Symfony\Component\HttpFoundation\Response', $response);
        $this->assertTrue($response->isRedirection());
        $this->assertSame($expectedStatusCode, $response->getStatusCode());
        $this->assertSame($expectedLocation, $response->headers->get('Location'));
    }

    public function provideHandleRedirectException()
    {
        return [
            [new HttpRedirectException('/foo/', Response::HTTP_MOVED_PERMANENTLY), Response::HTTP_MOVED_PERMANENTLY, '/foo/'],
            [new HttpRedirectException('/foo/', Response::HTTP_FOUND), Response::HTTP_FOUND, '/foo/'],
            [new HttpRedirectException('/foo/', Response::HTTP_SEE_OTHER), Response::HTTP_SEE_OTHER, '/foo/'],
            [new HttpRedirectException('/foo/', Response::HTTP_TEMPORARY_REDIRECT), Response::HTTP_TEMPORARY_REDIRECT, '/foo/'],
        ];
    }

    /**
     * @dataProvider provideHandleOtherException
     */
    public function testHandleOhterException(HttpException $exception, $expectedStatusCode, $expectedLocation)
    {
        $request = new Request();
        $response = new Response();

        $kernel = $this->getMock('Emonkak\Framework\KernelInterface');
        $kernel
            ->expects($this->once())
            ->method('handleException')
            ->with($this->identicalTo($request), $this->identicalTo($exception))
            ->willReturn($response);

        $middleware = new RedirectHandlerMiddleware($kernel);
        $this->assertSame($response, $middleware->handleException($request, $exception));
    }

    public function provideHandleOtherException()
    {
        return [
            [new HttpNotFoundException(),            200, null],
            [new HttpBadRequestException(),          200, null],
            [new HttpForbiddenException(),           200, null],
            [new HttpInternalServerErrorException(), 200, null],
            [new HttpServiceUnavailableException(),  200, null],
        ];
    }
}
