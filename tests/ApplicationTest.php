<?php

namespace Emonkak\Framework\Tests;

use Emonkak\Framework\Application;
use Emonkak\Framework\Exception\HttpException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class ApplicationTest extends \PHPUnit_Framework_TestCase
{
    public function testHandle()
    {
        $request = new Request();
        $response = new Response();

        $kernel = $this->getMock('Emonkak\Framework\KernelInterface');
        $kernel
            ->expects($this->once())
            ->method('handleRequest')
            ->with($this->identicalTo($request))
            ->willReturn($response);
        $kernel
            ->expects($this->never())
            ->method('handleException');

        $application = $this->getMockForTrait('Emonkak\Framework\Application');
        $application
            ->expects($this->once())
            ->method('getKernel')
            ->willReturn($kernel);
        $this->assertSame($response, $application->handle($request));
    }

    public function testHandleRequestThrowsHttpExeption()
    {
        $request = new Request();
        $response = new Response();
        $exception = new HttpException(200);

        $kernel = $this->getMock('Emonkak\Framework\KernelInterface');
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

        $application = $this->getMockForTrait('Emonkak\Framework\Application');
        $application
            ->expects($this->once())
            ->method('getKernel')
            ->willReturn($kernel);
        $this->assertSame($response, $application->handle($request));
    }

    public function testHandleRequestThrowsUncaughtExeption()
    {
        $request = new Request();
        $response = new Response();
        $exception = new \Exception();

        $kernel = $this->getMock('Emonkak\Framework\KernelInterface');
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
                $this->isInstanceOf('Emonkak\Framework\Exception\HttpInternalServerErrorException')
            )
            ->willReturn($response);

        $application = $this->getMockForTrait('Emonkak\Framework\Application');
        $application
            ->expects($this->once())
            ->method('getKernel')
            ->willReturn($kernel);
        $this->assertSame($response, $application->handle($request));
    }
}
