<?php

namespace Emonkak\Framework\Tests\Routing
{
    use Emonkak\Framework\Routing\RegexpRouter;
    use Symfony\Component\HttpFoundation\Request;

    class RegexpRouterTest extends \PHPUnit_Framework_TestCase
    {
        /**
         * @dataProvider provideMatch
         */
        public function testMatch($path, $pattern, $controller, $action, array $expectedParams)
        {
            $request = $this->getMock('Symfony\Component\HttpFoundation\Request');
            $request
                ->expects($this->once())
                ->method('getPathInfo')
                ->willReturn($path);
            $router = new RegexpRouter($pattern, $controller, $action);
            $match = $router->match($request);

            $this->assertNotNull($match);
            $this->assertSame($controller, $match->controller->getName());
            $this->assertSame($action, $match->action);
            $this->assertSame($expectedParams, $match->params);
        }

        public function provideMatch()
        {
            return [
                ['/test/',                '|^/test/|',                    'Emonkak\Framework\Tests\Routing\RegexpRouterTest\FooController', 'index', []],
                ['/test/show/123',        '|^/test/show/(\d+)|',          'Emonkak\Framework\Tests\Routing\RegexpRouterTest\FooController', 'show', ['123']],
                ['/test/between/123/456', '|^/test/between/(\d+)/(\d+)|', 'Emonkak\Framework\Tests\Routing\RegexpRouterTest\FooController', 'between', ['123', '456']],
            ];
        }

        /**
         * @dataProvider provideMatchReturnsNull
         */
        public function testMatchReturnsNull($path, $pattern, $controller, $action)
        {
            $request = $this->getMock('Symfony\Component\HttpFoundation\Request');
            $request
                ->expects($this->once())
                ->method('getPathInfo')
                ->willReturn($path);
            $router = new RegexpRouter($pattern, $controller, $action);
            $match = $router->match($request);

            $this->assertNull($match);
        }

        public function provideMatchReturnsNull()
        {
            return [
                ['/', '|^/test/|',                    'Emonkak\Framework\Tests\Routing\RegexpRouterTest\FooController', 'index'],
                ['/', '|^/test/show/(\d+)|',          'Emonkak\Framework\Tests\Routing\RegexpRouterTest\FooController', 'show'],
                ['/', '|^/test/between/(\d+)/(\d+)|', 'Emonkak\Framework\Tests\Routing\RegexpRouterTest\FooController', 'between'],
            ];
        }

        /**
         * @expectedException ReflectionException
         */
        public function testMatchThrowsRelectionException()
        {
            $request = new Request();
            $router = new RegexpRouter('||', 'Emonkak\Framework\Tests\Routing\RegexpRouterTest\IsNotFoundController', 'getIndex');
            $match = $router->match($request);
        }
    }
}

namespace Emonkak\Framework\Tests\Routing\RegexpRouterTest
{
    class FooController
    {
    }
}
