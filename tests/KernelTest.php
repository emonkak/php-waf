<?php

namespace Emonkak\Waf\Tests;

use Emonkak\Waf\Exception\HttpException;
use Emonkak\Waf\Kernel;
use Emonkak\Waf\Routing\MatchedRoute;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

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
        $request = new Request();
        $match = new MatchedRoute('StdClass', 'index', []);
        $controller = new \StdClass();
        $response = new Response();

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
        $request = new Request();

        $this->router
            ->expects($this->once())
            ->method('match')
            ->with($this->identicalTo($request))
            ->willReturn(null);

        $this->kernel->handleRequest($request);
    }

    public function testHandleException()
    {
        $request = new Request();
        $exception = new HttpException(404, ['X-Token' => 'token']);

        $response = $this->kernel->handleException($request, $exception);
        $this->assertInstanceOf('Symfony\Component\HttpFoundation\Response', $response);
        $this->assertEmpty($response->getContent());
        $this->assertSame($exception->getStatusCode(), $response->getStatusCode());
        $this->assertSame('token', $response->headers->get('X-Token'));
    }
}
