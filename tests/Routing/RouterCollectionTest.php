<?php

namespace Emonkak\Waf\Tests\Routing;

use Emonkak\Waf\Routing\MatchedRoute;
use Emonkak\Waf\Routing\RouterCollection;

class RouterCollectionTest extends \PHPUnit_Framework_TestCase
{
    public function testMatch()
    {
        $expectedResult = new MatchedRoute(new \stdClass(), 'index', []);

        $matchedRouter = $this->getMock('Emonkak\Waf\Routing\RouterInterface');
        $matchedRouter
            ->expects($this->once())
            ->method('match')
            ->willReturn($expectedResult);

        $nullRouter = $this->getMock('Emonkak\Waf\Routing\RouterInterface');
        $nullRouter
            ->expects($this->once())
            ->method('match')
            ->willReturn(null);

        $nerverCalledRouter = $this->getMock('Emonkak\Waf\Routing\RouterInterface');
        $nerverCalledRouter
            ->expects($this->never())
            ->method('match');

        $router = new RouterCollection([$nullRouter, $matchedRouter, $nerverCalledRouter]);
        $request = $this->getMock('Psr\Http\Message\RequestInterface');
        $this->assertSame($expectedResult, $router->match($request));

        $router = new RouterCollection([]);
        $this->assertNull($router->match($request));
    }

    public function testGetPattern()
    {
        $router1 = $this->getMock('Emonkak\Waf\Routing\RouterInterface');
        $router1
            ->expects($this->once())
            ->method('getPattern')
            ->willReturn('/foo/bar/(\d+)');

        $router2 = $this->getMock('Emonkak\Waf\Routing\RouterInterface');
        $router2
            ->expects($this->once())
            ->method('getPattern')
            ->willReturn('/foo/(?=bar/)');

        $router3 = $this->getMock('Emonkak\Waf\Routing\RouterInterface');
        $router3
            ->expects($this->once())
            ->method('getPattern')
            ->willReturn('/foo/\(bar\)');

        $router = new RouterCollection([$router1, $router2, $router3]);
        $this->assertSame('(/foo/bar/(?:\d+))|(/foo/(?=bar/))|(/foo/\(bar\))', $router->getPattern());
    }
}
