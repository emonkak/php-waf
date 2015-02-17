<?php

namespace Emonkak\Waf\Tests\Routing;

use Emonkak\Waf\Routing\RequestMatcherRouter;
use Symfony\Component\HttpFoundation\Request;

class RequestMatcherRouterTest extends \PHPUnit_Framework_TestCase
{
    public function testMatched()
    {
        $matcher = $this->getMock('Symfony\Component\HttpFoundation\RequestMatcherInterface');
        $matcher
            ->expects($this->once())
            ->method('matches')
            ->willReturn(true);

        $expectedResult = new \StdClass();

        $innerRouter = $this->getMock('Emonkak\Waf\Routing\RouterInterface');
        $innerRouter
            ->expects($this->once())
            ->method('match')
            ->willReturn($expectedResult);

        $router = new RequestMatcherRouter($innerRouter, $matcher);
        $request = new Request();

        $this->assertSame($expectedResult, $router->match($request));
    }

    public function testNotMatch()
    {
        $matcher = $this->getMock('Symfony\Component\HttpFoundation\RequestMatcherInterface');
        $matcher
            ->expects($this->once())
            ->method('matches')
            ->willReturn(false);

        $innerRouter = $this->getMock('Emonkak\Waf\Routing\RouterInterface');
        $innerRouter
            ->expects($this->never())
            ->method('match');

        $router = new RequestMatcherRouter($innerRouter, $matcher);
        $request = new Request();

        $this->assertNull($router->match($request));
    }

    public function testGetPattern()
    {
        $matcher = $this->getMock('Symfony\Component\HttpFoundation\RequestMatcherInterface');

        $innerRouter = $this->getMock('Emonkak\Waf\Routing\RouterInterface');
        $innerRouter
            ->expects($this->once())
            ->method('getPattern')
            ->willReturn('/foo/bar');

        $router = new RequestMatcherRouter($innerRouter, $matcher);

        $this->assertSame('/foo/bar', $router->getPattern());
    }
}
