<?php

namespace Emonkak\Framework\Tests\Routing;

use Emonkak\Framework\Routing\RequestMatcherRouter;
use Symfony\Component\HttpFoundation\Request;

class RequestMatcherRouterTest extends \PHPUnit_Framework_TestCase
{
    public function testMatchNg()
    {
        $matcher = $this->getMock('Symfony\Component\HttpFoundation\RequestMatcherInterface');
        $matcher
            ->expects($this->once())
            ->method('matches')
            ->willReturn(false);

        $innerRouter = $this->getMock('Emonkak\Framework\Routing\RouterInterface');
        $innerRouter
            ->expects($this->never())
            ->method('match');

        $router = new RequestMatcherRouter($innerRouter, $matcher);
        $request = new Request();

        $this->assertNull($router->match($request));
    }

    public function testMatchOk()
    {
        $matcher = $this->getMock('Symfony\Component\HttpFoundation\RequestMatcherInterface');
        $matcher
            ->expects($this->once())
            ->method('matches')
            ->willReturn(true);

        $expectedResult = new \StdClass();

        $innerRouter = $this->getMock('Emonkak\Framework\Routing\RouterInterface');
        $innerRouter
            ->expects($this->once())
            ->method('match')
            ->willReturn($expectedResult);

        $router = new RequestMatcherRouter($innerRouter, $matcher);
        $request = new Request();

        $this->assertSame($expectedResult, $router->match($request));
    }
}
