<?php

namespace Emonkak\Waf\Tests\Middleware;

use Emonkak\Waf\Exception\HttpException;
use Emonkak\Waf\Middleware\ProfilerMiddleware;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Profiler\Profile;

class ProfilerMiddlewareMiddlewareTest extends \PHPUnit_Framework_TestCase 
{
    public function testHandleRequest()
    {
        $request = new Request();
        $response = new Response();
        $profile = new Profile('token');

        $kernel = $this->getMock('Emonkak\Waf\KernelInterface');
        $kernel
            ->expects($this->once())
            ->method('handleRequest')
            ->with($this->identicalTo($request))
            ->willReturn($response);

        $profiler = $this->getMockBuilder('Symfony\Component\HttpKernel\Profiler\Profiler')
            ->disableOriginalConstructor()
            ->getMock();
        $profiler
            ->expects($this->once())
            ->method('collect')
            ->with($this->identicalTo($request), $this->identicalTo($response))
            ->willReturn($profile);
        $profiler
            ->expects($this->once())
            ->method('saveProfile')
            ->with($this->identicalTo($profile));

        $middleware = new ProfilerMiddleware($kernel, $profiler);
        $this->assertSame($response, $middleware->handleRequest($request));
    }

    public function testHandleException()
    {
        $request = new Request();
        $response = new Response();
        $profile = new Profile('token');
        $exception = new HttpException(404);

        $kernel = $this->getMock('Emonkak\Waf\KernelInterface');
        $kernel
            ->expects($this->once())
            ->method('handleException')
            ->with($this->identicalTo($request), $this->identicalTo($exception))
            ->willReturn($response);

        $profiler = $this->getMockBuilder('Symfony\Component\HttpKernel\Profiler\Profiler')
            ->disableOriginalConstructor()
            ->getMock();
        $profiler
            ->expects($this->once())
            ->method('collect')
            ->with($this->identicalTo($request), $this->identicalTo($response), $this->identicalTo($exception))
            ->willReturn($profile);
        $profiler
            ->expects($this->once())
            ->method('saveProfile')
            ->with($this->identicalTo($profile));

        $middleware = new ProfilerMiddleware($kernel, $profiler);
        $this->assertSame($response, $middleware->handleException($request, $exception));
    }
}
