<?php

namespace Emonkak\Waf\Tests\Action;

use Emonkak\Waf\Action\ActionDispatcherCollection;
use Emonkak\Waf\Routing\MatchedRoute;

class ActionDispatcherCollectionTest extends \PHPUnit_Framework_TestCase
{
    public function testDispatch()
    {
        $request = $this->getMock('Psr\Http\Message\RequestInterface');
        $match = new MatchedRoute('StdClass', 'index', []);
        $controller = new \StdClass();
        $response = $this->getMock('Psr\Http\Message\ResponseInterface');

        $dispatcher1 = $this->getMock('Emonkak\Waf\Action\ActionDispatcherInterface');
        $dispatcher1
            ->expects($this->once())
            ->method('canDispatch')
            ->with(
                $this->identicalTo($request),
                $this->identicalTo($match),
                $this->identicalTo($controller)
            )
            ->willReturn(false);
        $dispatcher1
            ->expects($this->never())
            ->method('dispatch');

        $dispatcher2 = $this->getMock('Emonkak\Waf\Action\ActionDispatcherInterface');
        $dispatcher2
            ->expects($this->once())
            ->method('canDispatch')
            ->with(
                $this->identicalTo($request),
                $this->identicalTo($match),
                $this->identicalTo($controller)
            )
            ->willReturn(true);
        $dispatcher2
            ->expects($this->once())
            ->method('dispatch')
            ->with(
                $this->identicalTo($request),
                $this->identicalTo($match),
                $this->identicalTo($controller)
            )
            ->willReturn($response);

        $dispatcher3 = $this->getMock('Emonkak\Waf\Action\ActionDispatcherInterface');
        $dispatcher3
            ->expects($this->never())
            ->method('canDispatch');
        $dispatcher3
            ->expects($this->never())
            ->method('dispatch');

        $collection = new ActionDispatcherCollection([$dispatcher1, $dispatcher2, $dispatcher3]);
        $this->assertSame($response, $collection->dispatch($request, $match, $controller));
    }

    /**
     * @expectedException \LogicException
     */
    public function testDispatchThrowsLogicException()
    {
        $request = $this->getMock('Psr\Http\Message\RequestInterface');
        $match = new MatchedRoute('StdClass', 'index', []);
        $controller = new \StdClass();

        $collection = new ActionDispatcherCollection([]);
        $collection->dispatch($request, $match, $controller);
    }

    public function testCanDispatch()
    {
        $request = $this->getMock('Psr\Http\Message\RequestInterface');
        $match = new MatchedRoute('StdClass', 'index', []);
        $controller = new \StdClass();

        $dispatcher1 = $this->getMock('Emonkak\Waf\Action\ActionDispatcherInterface');
        $dispatcher1
            ->expects($this->once())
            ->method('canDispatch')
            ->with(
                $this->identicalTo($request),
                $this->identicalTo($match),
                $this->identicalTo($controller)
            )
            ->willReturn(false);

        $dispatcher2 = $this->getMock('Emonkak\Waf\Action\ActionDispatcherInterface');
        $dispatcher2
            ->expects($this->once())
            ->method('canDispatch')
            ->with(
                $this->identicalTo($request),
                $this->identicalTo($match),
                $this->identicalTo($controller)
            )
            ->willReturn(true);

        $dispatcher3 = $this->getMock('Emonkak\Waf\Action\ActionDispatcherInterface');
        $dispatcher3
            ->expects($this->never())
            ->method('canDispatch');

        $collection = new ActionDispatcherCollection([$dispatcher1, $dispatcher2, $dispatcher3]);
        $this->assertTrue($collection->canDispatch($request, $match, $controller));

        $collection = new ActionDispatcherCollection([]);
        $this->assertFalse($collection->canDispatch($request, $match, $controller));
    }

    public function testGetIterator()
    {
        $request = $this->getMock('Psr\Http\Message\RequestInterface');
        $match = new MatchedRoute('StdClass', 'index', []);
        $controller = new \StdClass();

        $dispatcher1 = $this->getMock('Emonkak\Waf\Action\ActionDispatcherInterface');
        $dispatcher2 = $this->getMock('Emonkak\Waf\Action\ActionDispatcherInterface');
        $dispatcher3 = $this->getMock('Emonkak\Waf\Action\ActionDispatcherInterface');

        $collection = new ActionDispatcherCollection([$dispatcher1, $dispatcher2, $dispatcher3]);
        $this->assertSame([$dispatcher1, $dispatcher2, $dispatcher3], iterator_to_array($collection));
    }
}
