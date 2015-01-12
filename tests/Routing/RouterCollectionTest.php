<?php

namespace Emonkak\Framework\Tests\Routing;

use Emonkak\Framework\Routing\RouterCollection;
use Symfony\Component\HttpFoundation\Request;

class RouterCollectionTest extends \PHPUnit_Framework_TestCase
{
    public function testMatch()
    {
        $expectedResult = new \StdClass();

        $matchedRouter = $this->getMock('Emonkak\Framework\Routing\RouterInterface');
        $matchedRouter
            ->expects($this->once())
            ->method('match')
            ->willReturn($expectedResult);

        $nullRouter = $this->getMock('Emonkak\Framework\Routing\RouterInterface');
        $nullRouter
            ->expects($this->once())
            ->method('match')
            ->willReturn(null);

        $nerverCalledRouter = $this->getMock('Emonkak\Framework\Routing\RouterInterface');
        $nerverCalledRouter
            ->expects($this->never())
            ->method('match');

        $router = new RouterCollection([$nullRouter, $matchedRouter, $nerverCalledRouter]);
        $request = new Request();
        $this->assertSame($expectedResult, $router->match($request));

        $router = new RouterCollection([]);
        $this->assertNull($router->match($request));
    }
}
