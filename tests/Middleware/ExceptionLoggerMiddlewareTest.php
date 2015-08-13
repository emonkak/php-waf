<?php

namespace Emonkak\Waf\Middleware;

use Emonkak\Waf\Exception\HttpBadRequestException;
use Emonkak\Waf\Exception\HttpException;
use Emonkak\Waf\Exception\HttpForbiddenException;
use Emonkak\Waf\Exception\HttpInternalServerErrorException;
use Emonkak\Waf\Exception\HttpNotFoundException;
use Emonkak\Waf\Exception\HttpRedirectException;
use Emonkak\Waf\Exception\HttpServiceUnavailableException;
use Emonkak\Waf\Middleware\ExceptionLoggerMiddleware;
use Psr\Log\LogLevel;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class ExceptionLoggerMiddlewareTest extends \PHPUnit_Framework_TestCase
{
    public function testHandleRequest()
    {
        $request = new Request();
        $response = new Response();

        $kernel = $this->getMock('Emonkak\Waf\KernelInterface');
        $kernel
            ->expects($this->once())
            ->method('handleRequest')
            ->with($this->identicalTo($request))
            ->willReturn($response);

        $logger = $this->getMock('Psr\Log\LoggerInterface');

        $middleware = new ExceptionLoggerMiddleware($kernel, $logger);
        $this->assertSame($response, $middleware->handleRequest($request));
    }

    /**
     * @dataProvider provideHandleException
     */
    public function testHandleException(HttpException $exception, $expectedLogLevel)
    {
        $request = new Request();
        $response = new Response();

        $kernel = $this->getMock('Emonkak\Waf\KernelInterface');
        $kernel
            ->expects($this->once())
            ->method('handleException')
            ->with($this->identicalTo($request), $this->identicalTo($exception))
            ->willReturn($response);

        $logger = $this->getMock('Psr\Log\LoggerInterface');
        $logger
            ->expects($this->once())
            ->method('log')
            ->with(
                $this->identicalTo($expectedLogLevel),
                $this->matchesRegularExpression(
                    sprintf(
                        '/^Uncaught exception "%s" with message "%s" at .+ line \d+$/',
                        preg_quote(get_class($exception)),
                        preg_quote($exception->getMessage())
                    )
                ),
                $this->identicalTo(['exception' => $exception])
            );

        $middleware = new ExceptionLoggerMiddleware($kernel, $logger);

        $this->assertSame($response, $middleware->handleException($request, $exception));
    }

    public function provideHandleException()
    {
        return [
            [new HttpInternalServerErrorException('intrenal server error'), LogLevel::ERROR],
            [new HttpServiceUnavailableException('service unavailable'), LogLevel::ERROR],
            [new HttpNotFoundException('not found'), LogLevel::WARNING],
            [new HttpRedirectException('redirect'), LogLevel::INFO],
            [new HttpBadRequestException('bad request'), LogLevel::WARNING],
            [new HttpForbiddenException('forbidden'), LogLevel::WARNING],
        ];
    }

    /**
     * @dataProvider provideHandleNestException
     */
    public function testHandleNestException(HttpException $exception, $expectedLogLevel)
    {
        $request = new Request();
        $response = new Response();

        $kernel = $this->getMock('Emonkak\Waf\KernelInterface');
        $kernel
            ->expects($this->once())
            ->method('handleException')
            ->with($this->identicalTo($request), $this->identicalTo($exception))
            ->willReturn($response);

        $logger = $this->getMock('Psr\Log\LoggerInterface');
        $logger
            ->expects($this->once())
            ->method('log')
            ->with(
                LogLevel::ERROR,
                $this->matchesRegularExpression(
                    sprintf(
                        '/^Uncaught exception "%s" with message "%s" at .+? line \d+ - Caused by "%s" with message "%s" at .+? line \d+$/',
                        preg_quote(get_class($exception)),
                        preg_quote($exception->getMessage()),
                        preg_quote(get_class($exception->getPrevious())),
                        preg_quote($exception->getPrevious()->getMessage())
                    )
                ),
                $this->identicalTo(['exception' => $exception])
            );

        $middleware = new ExceptionLoggerMiddleware($kernel, $logger);

        $this->assertSame($response, $middleware->handleException($request, $exception));
    }

    public function provideHandleNestException()
    {
        return [
            [new HttpInternalServerErrorException('intrenal server error', new \RuntimeException('inner exception')), LogLevel::ERROR],
        ];
    }
}
