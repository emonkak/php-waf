<?php

namespace Emonkak\Waf\Tests\Routing;

use Emonkak\Waf\Routing\MatchedRoute;
use Emonkak\Waf\Routing\OptimizedRouterCollection;
use Symfony\Component\HttpFoundation\Request;

class OptimizedRouterCollectionTest extends \PHPUnit_Framework_TestCase
{
    public function testMatch()
    {
        $router1Result = new MatchedRoute(new \stdClass(), 'index', []);
        $router2Result = new MatchedRoute(new \stdClass(), 'index', []);
        $router3Result = new MatchedRoute(new \stdClass(), 'index', []);

        $router1 = $this->createRouterMock('/foo/show/(\d+)', $router1Result);
        $router2 = $this->createRouterMock('/foo/page/p(\d+)', $router2Result);
        $router3 = $this->createRouterMock('/foo/', $router3Result);

        $router = new OptimizedRouterCollection([$router1, $router2, $router3]);

        $request = Request::create('/foo/show/123');
        $this->assertSame($router1Result, $router->match($request));

        $request = Request::create('/foo/page/p123');
        $this->assertSame($router2Result, $router->match($request));

        $request = Request::create('/foo/');
        $this->assertSame($router3Result, $router->match($request));

        $request = Request::create('/bar/');
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

        $router = new OptimizedRouterCollection([$router1, $router2, $router3]);
        $this->assertSame('(/foo/bar/(?:\d+))|(/foo/(?=bar/))|(/foo/\(bar\))', $router->getPattern());
    }

    private function createRouterMock($returnPattern, $returnResult)
    {
        $router = $this->getMock('Emonkak\Waf\Routing\RouterInterface');
        $router
            ->expects($this->once())
            ->method('getPattern')
            ->willReturn($returnPattern);
        $router
            ->expects($this->once())
            ->method('match')
            ->willReturn($returnResult);
        return $router;
    }
}
