<?php

namespace Emonkak\Framework\Tests\Action;

use Emonkak\Framework\Action\ControllerEventDispatcher;
use Emonkak\Framework\Routing\MatchedRoute;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class ControllerEventDispatcherTest extends \PHPUnit_Framework_TestCase
{
    public function testDispatch()
    {
        $request = new Request();
        $response = new Response();

        $controllerMock = $this->getMock('Emonkak\Framework\Controller\ControllerEventListenerInterface');
        $controllerMock
            ->expects($this->once())
            ->method('onRequest')
            ->with($this->identicalTo($request));
        $controllerMock
            ->expects($this->once())
            ->method('onResponse')
            ->with($this->identicalTo($request), $this->identicalTo($response));

        $dispatcherMock = $this->getMock('Emonkak\Framework\Action\ActionDispatcherInterface');

        $match = new MatchedRoute(new \ReflectionObject($dispatcherMock), 'index', []);

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
        $request = new Request();
        $response = new Response();

        $controllerMock = $this->getMock('Emonkak\Framework\Controller\ControllerEventListenerInterface');
        $controllerMock
            ->expects($this->once())
            ->method('onRequest')
            ->with($this->identicalTo($request))
            ->willReturn($response);
        $controllerMock
            ->expects($this->never())
            ->method('onResponse');

        $dispatcherMock = $this->getMock('Emonkak\Framework\Action\ActionDispatcherInterface');
        $dispatcherMock
            ->expects($this->never())
            ->method('dispatch');

        $match = new MatchedRoute(new \ReflectionObject($dispatcherMock), 'index', []);

        $dispatcher = new ControllerEventDispatcher($dispatcherMock);
        $this->assertSame($response, $dispatcher->dispatch($request, $match, $controllerMock));
    }

    public function testDispatchReturnsResponseOnResponse()
    {
        $request = new Request();
        $response1 = new Response();
        $response2 = new Response();

        $controllerMock = $this->getMock('Emonkak\Framework\Controller\ControllerEventListenerInterface');
        $controllerMock
            ->expects($this->once())
            ->method('onRequest')
            ->with($this->identicalTo($request));
        $controllerMock
            ->expects($this->once())
            ->method('onResponse')
            ->with($this->identicalTo($request), $this->identicalTo($response1))
            ->willReturn($response2);

        $dispatcherMock = $this->getMock('Emonkak\Framework\Action\ActionDispatcherInterface');

        $match = new MatchedRoute(new \ReflectionObject($dispatcherMock), 'index', []);

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
        $request = new Request();
        $match = new MatchedRoute(new \ReflectionClass('StdClass'), 'index', []);
        $controller = new \StdClass();

        $dispatcherMock = $this->getMock('Emonkak\Framework\Action\ActionDispatcherInterface');
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
