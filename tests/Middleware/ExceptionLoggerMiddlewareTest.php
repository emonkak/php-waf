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
     * @dataProvider provideHandleCriticalException
     */
    public function testHandleCriticalException(HttpException $exception)
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
            ->method('critical')
            ->with(
                $this->matchesRegularExpression($this->createLogMessagePattern($exception)),
                $this->identicalTo(['exception' => $exception])
            )
            ->willReturn($response);

        $middleware = new ExceptionLoggerMiddleware($kernel, $logger);
        $this->assertSame($response, $middleware->handleException($request, $exception));
    }

    public function provideHandleCriticalException()
    {
        return [
            [new HttpInternalServerErrorException('intrenal server error')],
            [new HttpServiceUnavailableException('service unavailable')],
        ];
    }

    /**
     * @dataProvider provideHandleErrorException
     */
    public function testHandleErorrException(HttpException $exception)
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
            ->method('error')
            ->with(
                $this->matchesRegularExpression($this->createLogMessagePattern($exception)),
                $this->identicalTo(['exception' => $exception])
            )
            ->willReturn($response);

        $middleware = new ExceptionLoggerMiddleware($kernel, $logger);
        $this->assertSame($response, $middleware->handleException($request, $exception));
    }

    public function provideHandleErrorException()
    {
        return [
            [new HttpNotFoundException('not found')],
            [new HttpBadRequestException('bad request')],
            [new HttpForbiddenException('forbidden')],
        ];
    }

    private function createLogMessagePattern(\Exception $exception)
    {
        return sprintf(
            '/^Uncaught exception "%s" with message ".*?" at .+ line \d+$/',
            preg_quote(get_class($exception))
        );
    }
}
