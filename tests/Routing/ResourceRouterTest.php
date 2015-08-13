<?php

namespace Emonkak\Waf\Tests\Routing
{
    use Emonkak\Waf\Exception\HttpNotFoundException;
    use Emonkak\Waf\Exception\HttpRedirectException;
    use Emonkak\Waf\Routing\ResourceRouter;
    use Symfony\Component\HttpFoundation\Request;

    class ResourceRouterTest extends \PHPUnit_Framework_TestCase
    {
        /**
         * @dataProvider provideMatch
         */
        public function testMatch($url, $prefix, $controller, $expectedAction, $expectedParams)
        {
            $request = Request::create($url);
            $router = new ResourceRouter($prefix, $controller);
            $match = $router->match($request);

            $this->assertNotNull($match);
            $this->assertSame($controller, $match->controller);
            $this->assertSame($expectedAction, $match->action);
            $this->assertSame($expectedParams, $match->params);
        }

        public function provideMatch()
        {
            return [
                ['/',                    '/',     'Emonkak\Waf\Tests\Routing\ResourceRouterTest\FooController', 'index', []],
                ['/123',                 '/',     'Emonkak\Waf\Tests\Routing\ResourceRouterTest\FooController', 'show', ['123']],
                ['/123/',                '/',     'Emonkak\Waf\Tests\Routing\ResourceRouterTest\FooController', 'show', ['123']],
                ['/123/edit',            '/',     'Emonkak\Waf\Tests\Routing\ResourceRouterTest\FooController', 'edit', ['123']],
                ['/123/between/456',     '/',     'Emonkak\Waf\Tests\Routing\ResourceRouterTest\FooController', 'between', ['123', '456']],
                ['/foo/',                '/foo/', 'Emonkak\Waf\Tests\Routing\ResourceRouterTest\FooController', 'index', []],
                ['/foo/123',             '/foo/', 'Emonkak\Waf\Tests\Routing\ResourceRouterTest\FooController', 'show', ['123']],
                ['/foo/123/',            '/foo/', 'Emonkak\Waf\Tests\Routing\ResourceRouterTest\FooController', 'show', ['123']],
                ['/foo/123/edit',        '/foo/', 'Emonkak\Waf\Tests\Routing\ResourceRouterTest\FooController', 'edit', ['123']],
                ['/foo/123/between/456', '/foo/', 'Emonkak\Waf\Tests\Routing\ResourceRouterTest\FooController', 'between', ['123', '456']],
            ];
        }

        /**
         * @dataProvider provideMatchReturnsNull
         */
        public function testMatchReturnsNull($url, $prefix, $controller)
        {
            $request = Request::create($url);
            $router = new ResourceRouter($prefix, $controller);
            $this->assertNull($router->match($request));
        }

        public function provideMatchReturnsNull()
        {
            return [
                ['/',     '/foo/', 'Emonkak\Waf\Tests\Routing\ResourceRouterTest\FooController'],
                ['/FOO',  '/foo/', 'Emonkak\Waf\Tests\Routing\ResourceRouterTest\FooController'],
                ['/FOO/', '/foo/', 'Emonkak\Waf\Tests\Routing\ResourceRouterTest\FooController'],
                ['/bar/', '/foo/', 'Emonkak\Waf\Tests\Routing\ResourceRouterTest\FooController'],
            ];
        }

        /**
         * @expectedException Emonkak\Waf\Exception\HttpRedirectException
         * @dataProvider provideMatchHttpRedirectException
         */
        public function testMatchThrowsHttpRedirectException($url, $prefix, $controller, $expectedLocation)
        {
            $request = Request::create($url);
            $router = new ResourceRouter($prefix, $controller);

            try {
                $router->match($request);
            } catch (HttpRedirectException $e) {
                $this->assertSame(301, $e->getStatusCode());
                $this->assertSame(['Location' => $expectedLocation], $e->getHeaders());
                throw $e;
            }
        }

        public function provideMatchHttpRedirectException()
        {
            return [
                ['/foo',         '/foo/',     'Emonkak\Waf\Tests\Routing\ResourceRouterTest\FooController', '/foo/'],
                ['/foo?bar=baz', '/foo/',     'Emonkak\Waf\Tests\Routing\ResourceRouterTest\FooController', '/foo/?bar=baz'],
                ['/foo/bar',     '/foo/bar/', 'Emonkak\Waf\Tests\Routing\ResourceRouterTest\FooController', '/foo/bar/'],
            ];
        }

        /**
         * @dataProvider provideGetPattern
         */
        public function testGetPattern($prefix, $controller, $expectedPattern)
        {
            $router = new ResourceRouter($prefix, $controller);
            $this->assertSame($expectedPattern, $router->getPattern());
        }

        public function provideGetPattern()
        {
            return [
                ['/foo/', 'Emonkak\Waf\Tests\Routing\ResourceRouterTest\FooController', '/foo/'],
                ['/foo/.*/', 'Emonkak\Waf\Tests\Routing\ResourceRouterTest\FooController', '/foo/\\.\\*/'],
            ];
        }
    }
}

namespace Emonkak\Waf\Tests\Routing\ResourceRouterTest
{
    class FooController
    {
    }
}
