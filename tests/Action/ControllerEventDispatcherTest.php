<?php

namespace Emonkak\Waf\Tests\Action;

use Emonkak\Waf\Action\ControllerEventDispatcher;
use Emonkak\Waf\Routing\MatchedRoute;

class ControllerEventDispatcherTest extends \PHPUnit_Framework_TestCase
{
    public function testDispatch()
    {
        $request = $this->getMock('Psr\Http\Message\RequestInterface');
        $response = $this->getMock('Psr\Http\Message\ResponseInterface');

        $controllerMock = $this->getMock('Emonkak\Waf\Controller\ControllerEventListenerInterface');
        $controllerMock
            ->expects($this->once())
            ->method('onRequest')
            ->with($this->identicalTo($request));
        $controllerMock
            ->expects($this->once())
            ->method('onResponse')
            ->with($this->identicalTo($request), $this->identicalTo($response));

        $dispatcherMock = $this->getMock('Emonkak\Waf\Action\ActionDispatcherInterface');

        $match = new MatchedRoute(get_class($dispatcherMock), 'index', []);

        $dispatcherMock
            ->expects($this->once())
            ->method('dispatch')
            ->with(
                $this->identicalTo($request),
                $this->identicalTo($match),
                $this->identicalTo($controllerMock)
            )
            ->willReturn($response);

        $dispatcher = new ControllerEventDispatcher($dispatcherMock);
        $this->assertSame($response, $dispatcher->dispatch($request, $match, $controllerMock));
    }

    public function testDispatchReturnsResponseOnRequest()
    {
        $request = $this->getMock('Psr\Http\Message\RequestInterface');
        $response = $this->getMock('Psr\Http\Message\ResponseInterface');

        $controllerMock = $this->getMock('Emonkak\Waf\Controller\ControllerEventListenerInterface');
        $controllerMock
            ->expects($this->once())
            ->method('onRequest')
            ->with($this->identicalTo($request))
            ->willReturn($response);
        $controllerMock
            ->expects($this->never())
            ->method('onResponse');

        $dispatcherMock = $this->getMock('Emonkak\Waf\Action\ActionDispatcherInterface');
        $dispatcherMock
            ->expects($this->never())
            ->method('dispatch');

        $match = new MatchedRoute(get_class($dispatcherMock), 'index', []);

        $dispatcher = new ControllerEventDispatcher($dispatcherMock);
        $this->assertSame($response, $dispatcher->dispatch($request, $match, $controllerMock));
    }

    public function testDispatchReturnsResponseOnResponse()
    {
        $request = $this->getMock('Psr\Http\Message\RequestInterface');
        $response1 = $this->getMock('Psr\Http\Message\ResponseInterface');
        $response2 = $this->getMock('Psr\Http\Message\ResponseInterface');

        $controllerMock = $this->getMock('Emonkak\Waf\Controller\ControllerEventListenerInterface');
        $controllerMock
            ->expects($this->once())
            ->method('onRequest')
            ->with($this->identicalTo($request));
        $controllerMock
            ->expects($this->once())
            ->method('onResponse')
            ->with($this->identicalTo($request), $this->identicalTo($response1))
            ->willReturn($response2);

        $dispatcherMock = $this->getMock('Emonkak\Waf\Action\ActionDispatcherInterface');

        $match = new MatchedRoute(get_class($dispatcherMock), 'index', []);

        $dispatcherMock
            ->expects($this->once())
            ->method('dispatch')
            ->with(
                $this->identicalTo($request),
                $this->identicalTo($match),
                $this->identicalTo($controllerMock)
            )
            ->willReturn($response1);

        $dispatcher = new ControllerEventDispatcher($dispatcherMock);
        $this->assertSame($response2, $dispatcher->dispatch($request, $match, $controllerMock));
    }

    public function testCanDispatch()
    {
        $request = $this->getMock('Psr\Http\Message\RequestInterface');
        $match = new MatchedRoute('StdClass', 'index', []);
        $controller = new \StdClass();

        $dispatcherMock = $this->getMock('Emonkak\Waf\Action\ActionDispatcherInterface');
        $dispatcherMock
            ->expects($this->at(0))
            ->method('canDispatch')
            ->with(
                $this->identicalTo($request),
                $this->identicalTo($match),
                $this->identicalTo($controller)
            )
            ->willReturn(false);
        $dispatcherMock
            ->expects($this->at(1))
            ->method('canDispatch')
            ->with(
                $this->identicalTo($request),
                $this->identicalTo($match),
                $this->identicalTo($controller)
            )
            ->willReturn(true);

        $dispatcher = new ControllerEventDispatcher($dispatcherMock);
        $this->assertFalse($dispatcher->canDispatch($request, $match, $controller));
        $this->assertTrue($dispatcher->canDispatch($request, $match, $controller));
    }
}
