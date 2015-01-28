<?php

namespace Emonkak\Framework\Middleware;

use Emonkak\Framework\Exception\HttpBadRequestException;
use Emonkak\Framework\Exception\HttpException;
use Emonkak\Framework\Exception\HttpForbiddenException;
use Emonkak\Framework\Exception\HttpInternalServerErrorException;
use Emonkak\Framework\Exception\HttpNotFoundException;
use Emonkak\Framework\Exception\HttpRedirectException;
use Emonkak\Framework\Exception\HttpServiceUnavailableException;
use Emonkak\Framework\Middleware\ExceptionLoggerMiddleware;
use Psr\Log\LogLevel;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class ExceptionLoggerMiddlewareTest extends \PHPUnit_Framework_TestCase
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

        $kernel = $this->getMock('Emonkak\Framework\KernelInterface');
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
            [new HttpInternalServerErrorException('intrenal server error'), LogLevel::EMERGENCY],
            [new HttpServiceUnavailableException('service unavailable'), LogLevel::EMERGENCY],
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

        $kernel = $this->getMock('Emonkak\Framework\KernelInterface');
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
                LogLevel::EMERGENCY,
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
            [new HttpInternalServerErrorException('intrenal server error', new \RuntimeException('inner exception')), LogLevel::EMERGENCY],
        ];
    }
}
