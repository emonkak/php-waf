<?php

namespace Emonkak\Waf\Tests\Routing;

use Emonkak\Waf\Routing\MethodMatcherRouter;

class MethodMatcherRouterTest extends \PHPUnit_Framework_TestCase
{
    public function testMatched()
    {
        $expectedResult = new \stdClass();

        $innerRouter = $this->getMock('Emonkak\Waf\Routing\RouterInterface');
        $innerRouter
            ->expects($this->once())
            ->method('match')
            ->willReturn($expectedResult);

        $router = new MethodMatcherRouter($innerRouter, 'GET');

        $request = $this->getMock('Psr\Http\Message\RequestInterface');
        $request
            ->expects($this->any())
            ->method('getMethod')
            ->willReturn('GET');

        $this->assertSame($expectedResult, $router->match($request));
    }

    public function testNotMatch()
    {
        $expectedResult = new \stdClass();

        $innerRouter = $this->getMock('Emonkak\Waf\Routing\RouterInterface');
        $innerRouter
            ->expects($this->never())
            ->method('match');

        $router = new MethodMatcherRouter($innerRouter, 'GET');

        $request = $this->getMock('Psr\Http\Message\RequestInterface');
        $request
            ->expects($this->any())
            ->method('getMethod')
            ->willReturn('POST');

        $this->assertNull($router->match($request));
    }

    public function testGetPattern()
    {
        $innerRouter = $this->getMock('Emonkak\Waf\Routing\RouterInterface');
        $innerRouter
            ->expects($this->once())
            ->method('getPattern')
            ->willReturn('/foo/bar');

        $router = new MethodMatcherRouter($innerRouter, 'GET');

        $this->assertSame('/foo/bar', $router->getPattern());
    }
}
