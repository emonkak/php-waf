<?php

namespace Emonkak\Waf\Tests;

use Emonkak\Waf\Exception\HttpException;
use Emonkak\Waf\KernelInterface;

class ApplicationTraitTest extends \PHPUnit_Framework_TestCase
{
    public function testHandle()
    {
        $request = $this->getMock('Psr\Http\Message\RequestInterface');
        $response = $this->getMock('Psr\Http\Message\ResponseInterface');

        $kernel = $this->getMock('Emonkak\Waf\KernelInterface');
        $kernel
            ->expects($this->once())
            ->method('handleRequest')
            ->with($this->identicalTo($request))
            ->willReturn($response);
        $kernel
            ->expects($this->never())
            ->method('handleException');

        $application = $this->createApplicationMock($kernel, $request, $response);
        $this->assertSame($response, $application->handle($request));
    }

    public function testHandleRequestThrowsHttpExeption()
    {
        $request = $this->getMock('Psr\Http\Message\RequestInterface');
        $response = $this->getMock('Psr\Http\Message\ResponseInterface');
        $exception = new HttpException(200);

        $kernel = $this->getMock('Emonkak\Waf\KernelInterface');
        $kernel
            ->expects($this->once())
            ->method('handleRequest')
            ->with($this->identicalTo($request))
            ->will($this->throwException($exception));
        $kernel
            ->expects($this->once())
            ->method('handleException')
            ->with($this->identicalTo($request), $this->identicalTo($exception))
            ->willReturn($response);

        $application = $this->createApplicationMock($kernel, $request, $response);
        $this->assertSame($response, $application->handle($request));
    }

    public function testHandleRequestThrowsUncaughtExeption()
    {
        $request = $this->getMock('Psr\Http\Message\RequestInterface');
        $response = $this->getMock('Psr\Http\Message\ResponseInterface');
        $exception = new \Exception();

        $kernel = $this->getMock('Emonkak\Waf\KernelInterface');
        $kernel
            ->expects($this->once())
            ->method('handleRequest')
            ->with($this->identicalTo($request))
            ->will($this->throwException($exception));
        $kernel
            ->expects($this->once())
            ->method('handleException')
            ->with(
                $this->identicalTo($request),
                $this->isInstanceOf('Emonkak\Waf\Exception\HttpInternalServerErrorException')
            )
            ->willReturn($response);

        $application = $this->createApplicationMock($kernel, $request, $response);
        $this->assertSame($response, $application->handle($request));
    }

    private function createApplicationMock(KernelInterface $kernel)
    {
        $application = $this->getMockForTrait('Emonkak\Waf\ApplicationTrait');
        $application
            ->expects($this->once())
            ->method('getKernel')
            ->willReturn($kernel);
        return $application;
    }
}
