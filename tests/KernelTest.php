<?php

namespace Emonkak\Waf\Tests;

use Emonkak\Waf\Exception\HttpException;
use Emonkak\Waf\Kernel;
use Emonkak\Waf\Routing\MatchedRoute;

class KernelTest extends \PHPUnit_Framework_TestCase
{
    public function setUp()
    {
        $this->kernel = new Kernel(
            $this->router = $this->getMock('Emonkak\Waf\Routing\RouterInterface'),
            $this->instantiator = $this->getMock('Emonkak\Waf\Instantiator\InstantiatorInterface'),
            $this->actionDispatcher = $this->getMock('Emonkak\Waf\Action\ActionDispatcherInterface')
        );
    }

    public function testHandleRequest()
    {
        $request = $this->getMock('Psr\Http\Message\RequestInterface');
        $match = new MatchedRoute('StdClass', 'index', []);
        $controller = new \StdClass();
        $response = $this->getMock('Psr\Http\Message\ResponseInterface');

        $this->router
            ->expects($this->once())
            ->method('match')
            ->with($this->identicalTo($request))
            ->willReturn($match);

        $this->instantiator
            ->expects($this->once())
            ->method('instantiate')
            ->with($this->identicalTo($match->controller))
            ->willReturn($controller);

        $this->actionDispatcher
            ->expects($this->once())
            ->method('dispatch')
            ->with(
                $this->identicalTo($request),
                $this->identicalTo($match),
                $this->identicalTo($controller)
            )
            ->willReturn($response);

        $this->assertSame($response, $this->kernel->handleRequest($request));
    }

    /**
     * @expectedException Emonkak\Waf\Exception\HttpNotFoundException
     */
    public function testHandleRequestThrowsHttpNotfoundException()
    {
        $request = $this->getMock('Psr\Http\Message\RequestInterface');

        $this->router
            ->expects($this->once())
            ->method('match')
            ->with($this->identicalTo($request))
            ->willReturn(null);

        $this->kernel->handleRequest($request);
    }

    public function testHandleException()
    {
        $request = $this->getMock('Psr\Http\Message\RequestInterface');
        $exception = new HttpException(404, ['X-Token' => 'token']);

        $response = $this->kernel->handleException($request, $exception);
        $this->assertInstanceOf('Psr\Http\Message\ResponseInterface', $response);
        $this->assertEmpty((string) $response->getBody());
        $this->assertSame($exception->getStatusCode(), $response->getStatusCode());
        $this->assertSame(['token'], $response->getHeader('X-Token'));
    }
}
