<?php

namespace Emonkak\Waf\Tests\Routing
{
    use Emonkak\Waf\Routing\PatternRouter;

    class PatternRouterTest extends \PHPUnit_Framework_TestCase
    {
        /**
         * @dataProvider provideMatch
         */
        public function testMatch($path, $pattern, $controller, $action, array $expectedParams)
        {
            $request = $this->createRequestMock($path);
            $router = new PatternRouter($pattern, $controller, $action);
            $match = $router->match($request);

            $this->assertNotNull($match);
            $this->assertSame($controller, $match->controller);
            $this->assertSame($action, $match->action);
            $this->assertSame($expectedParams, $match->params);
        }

        public function provideMatch()
        {
            return [
                ['/test/',                '/test/',                    'Emonkak\Waf\Tests\Routing\PatternRouter\FooController', 'index', []],
                ['/test/show/123',        '/test/show/(\d+)',          'Emonkak\Waf\Tests\Routing\PatternRouter\FooController', 'show', ['123']],
                ['/test/page/p123',       '/test/page/p(\d+)',         'Emonkak\Waf\Tests\Routing\PatternRouter\FooController', 'page', ['123']],
                ['/test/between/123/456', '/test/between/(\d+)/(\d+)', 'Emonkak\Waf\Tests\Routing\PatternRouter\FooController', 'between', ['123', '456']],
            ];
        }

        /**
         * @dataProvider provideMatchReturnsNull
         */
        public function testMatchReturnsNull($path, $pattern, $controller, $action)
        {
            $request = $this->createRequestMock($path);
            $router = new PatternRouter($pattern, $controller, $action);
            $match = $router->match($request);

            $this->assertNull($match);
        }

        public function provideMatchReturnsNull()
        {
            return [
                ['/test',             '/test/',                    'Emonkak\Waf\Tests\Routing\PatternRouter\FooController', 'index'],
                ['/test/show/foo',    '/test/show/(\d+)',          'Emonkak\Waf\Tests\Routing\PatternRouter\FooController', 'show'],
                ['/test/page/123',    '/test/page/p(\d+)',         'Emonkak\Waf\Tests\Routing\PatternRouter\FooController', 'page'],
                ['/test/between/123', '/test/between/(\d+)/(\d+)', 'Emonkak\Waf\Tests\Routing\PatternRouter\FooController', 'between'],
            ];
        }

        /**
         * @dataProvider provideGetPattern
         */
        public function testGetPattern($pattern, $controller, $action)
        {
            $router = new PatternRouter($pattern, $controller, $action);
            $this->assertSame($pattern, $router->getPattern());
        }

        public function provideGetPattern()
        {
            return [
                ['/foo/', 'Emonkak\Waf\Tests\Routing\PatternRouterTest\FooController', 'index'],
                ['/foo/(.*?)/', 'Emonkak\Waf\Tests\Routing\PatternRouterTest\FooController', 'index'],
            ];
        }

        private function createRequestMock($path)
        {
            $uri = $this->getMock('Psr\Http\Message\UriInterface');
            $uri
                ->expects($this->any())
                ->method('getPath')
                ->willReturn($path);

            $request = $this->getMock('Psr\Http\Message\RequestInterface');
            $request
                ->expects($this->any())
                ->method('getUri')
                ->willReturn($uri);

            return $request;
        }
    }
}

namespace Emonkak\Waf\Tests\Routing\PatternRouterTest
{
    class FooController
    {
    }
}
