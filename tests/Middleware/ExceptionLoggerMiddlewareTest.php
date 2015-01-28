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
    public function testHandleException(HttpException $exception, array $logLevels, $expectedLogLevel)
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
        $middleware->setLogLevels($logLevels);

        $this->assertSame($response, $middleware->handleException($request, $exception));
    }

    public function provideHandleException()
    {
        return [
            [new HttpInternalServerErrorException('intrenal server error'), [], LogLevel::EMERGENCY],
            [new HttpServiceUnavailableException('service unavailable'), [], LogLevel::EMERGENCY],
            [new HttpNotFoundException('not found'), [], LogLevel::WARNING],
            [new HttpNotFoundException('not found'), [404 => LogLevel::INFO], LogLevel::INFO],
            [new HttpRedirectException('redirect'), [], LogLevel::INFO],
            [new HttpBadRequestException('bad request'), [], LogLevel::WARNING],
            [new HttpForbiddenException('forbidden'), [], LogLevel::WARNING],
        ];
    }

    public function testHandleNestException()
    {
        $request = new Request();
        $response = new Response();
        $exception = new HttpInternalServerErrorException('internal server error', new \RuntimeException('runtime exception'));

        $kernel = $this->getMock('Emonkak\Framework\KernelInterface');
        $kernel
            ->expects($this->once())
            ->method('handleException')
            ->with($this->identicalTo($request), $this->identicalTo($exception))
            ->willReturn($response);

        $logger = $this->getMock('Psr\Log\LoggerInterface');
        $logger
            ->expects($this->at(0))
            ->method('log')
            ->with(
                LogLevel::EMERGENCY,
                $this->matchesRegularExpression(
                    sprintf(
                        '/^Uncaught exception "%s" with message "%s" at .+ line \d+$/',
                        preg_quote(get_class($exception)),
                        preg_quote($exception->getMessage())
                    )
                ),
                $this->identicalTo(['exception' => $exception])
            );
        $logger
            ->expects($this->at(1))
            ->method('log')
            ->with(
                LogLevel::EMERGENCY,
                $this->matchesRegularExpression(
                    sprintf(
                        '/^Caused by "%s" with message "%s" at .+ line \d+$/',
                        preg_quote(get_class($exception->getPrevious())),
                        preg_quote($exception->getPrevious()->getMessage())
                    )
                ),
                $this->identicalTo(['exception' => $exception->getPrevious()])
            );

        $middleware = new ExceptionLoggerMiddleware($kernel, $logger);

        $this->assertSame($response, $middleware->handleException($request, $exception));
    }
}
