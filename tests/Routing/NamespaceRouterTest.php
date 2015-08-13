<?php

namespace Emonkak\Waf\Tests\Routing
{
    use Emonkak\Waf\Exception\HttpNotFoundException;
    use Emonkak\Waf\Exception\HttpRedirectException;
    use Emonkak\Waf\Routing\NamespaceRouter;
    use Symfony\Component\HttpFoundation\Request;

    class NamespaceRouterTest extends \PHPUnit_Framework_TestCase
    {
        /**
         * @dataProvider provideMatch
         */
        public function testMatch($url, $prefix, $namespace, $expectedController, $expectedAction, $expectedParams)
        {
            $request = Request::create($url);
            $router = new NamespaceRouter($prefix, $namespace);
            $match = $router->match($request);

            $this->assertNotNull($match);
            $this->assertSame($expectedController, $match->controller);
            $this->assertSame($expectedAction, $match->action);
            $this->assertSame($expectedParams, $match->params);
        }

        public function provideMatch()
        {
            return [
                ['/',                         '/',      'Emonkak\Waf\Tests\Routing\NamespaceRouterTest',      'Emonkak\Waf\Tests\Routing\NamespaceRouterTest\IndexController', 'index', []],
                ['/index/',                   '/',      'Emonkak\Waf\Tests\Routing\NamespaceRouterTest',      'Emonkak\Waf\Tests\Routing\NamespaceRouterTest\IndexController', 'index', []],
                ['/index/index',              '/',      'Emonkak\Waf\Tests\Routing\NamespaceRouterTest',      'Emonkak\Waf\Tests\Routing\NamespaceRouterTest\IndexController', 'index', []],
                ['/foo/',                     '/',      'Emonkak\Waf\Tests\Routing\NamespaceRouterTest',      'Emonkak\Waf\Tests\Routing\NamespaceRouterTest\FooController', 'index', []],
                ['/foo/index',                '/',      'Emonkak\Waf\Tests\Routing\NamespaceRouterTest',      'Emonkak\Waf\Tests\Routing\NamespaceRouterTest\FooController', 'index', []],
                ['/foo/show/',                '/',      'Emonkak\Waf\Tests\Routing\NamespaceRouterTest',      'Emonkak\Waf\Tests\Routing\NamespaceRouterTest\FooController', 'show', []],
                ['/foo/show/123',             '/',      'Emonkak\Waf\Tests\Routing\NamespaceRouterTest',      'Emonkak\Waf\Tests\Routing\NamespaceRouterTest\FooController', 'show', ['123']],
                ['/foo/between/123/456',      '/',      'Emonkak\Waf\Tests\Routing\NamespaceRouterTest',      'Emonkak\Waf\Tests\Routing\NamespaceRouterTest\FooController', 'between', ['123', '456']],
                ['/foo_bar/',                 '/',      'Emonkak\Waf\Tests\Routing\NamespaceRouterTest',      'Emonkak\Waf\Tests\Routing\NamespaceRouterTest\FooBarController', 'index', []],
                ['/hoge/',                    '/hoge/', 'Emonkak\Waf\Tests\Routing\NamespaceRouterTest\Hoge', 'Emonkak\Waf\Tests\Routing\NamespaceRouterTest\Hoge\IndexController', 'index', []],
                ['/hoge/index/',              '/hoge/', 'Emonkak\Waf\Tests\Routing\NamespaceRouterTest\Hoge', 'Emonkak\Waf\Tests\Routing\NamespaceRouterTest\Hoge\IndexController', 'index', []],
                ['/hoge/index/index',         '/hoge/', 'Emonkak\Waf\Tests\Routing\NamespaceRouterTest\Hoge', 'Emonkak\Waf\Tests\Routing\NamespaceRouterTest\Hoge\IndexController', 'index', []],
                ['/hoge/bar/show/',           '/hoge/', 'Emonkak\Waf\Tests\Routing\NamespaceRouterTest\Hoge', 'Emonkak\Waf\Tests\Routing\NamespaceRouterTest\Hoge\BarController', 'show', []],
                ['/hoge/bar/show/123',        '/hoge/', 'Emonkak\Waf\Tests\Routing\NamespaceRouterTest\Hoge', 'Emonkak\Waf\Tests\Routing\NamespaceRouterTest\Hoge\BarController', 'show', ['123']],
                ['/hoge/bar/between/123/456', '/hoge/', 'Emonkak\Waf\Tests\Routing\NamespaceRouterTest\Hoge', 'Emonkak\Waf\Tests\Routing\NamespaceRouterTest\Hoge\BarController', 'between', ['123', '456']],
            ];
        }

        /**
         * @dataProvider provideMatchThrowsHttpNotFoundException
         * @expectedException Emonkak\Waf\Exception\HttpNotFoundException
         */
        public function testMatchThrowsHttpNotFoundException($url, $prefix, $namespace)
        {
            $request = Request::create($url);
            $router = new NamespaceRouter($prefix, $namespace);
            $router->match($request);
        }

        public function provideMatchThrowsHttpNotFoundException()
        {
            return [
                ['/Foo/',      '/',      'Emonkak\Waf\Tests\Routing\NamespaceRouterTest'],
                ['/FOO/',      '/',      'Emonkak\Waf\Tests\Routing\NamespaceRouterTest'],
                ['/bar/',      '/',      'Emonkak\Waf\Tests\Routing\NamespaceRouterTest'],
                ['/Foo_Bar/',  '/',      'Emonkak\Waf\Tests\Routing\NamespaceRouterTest'],
                ['/FOO_BAR/',  '/',      'Emonkak\Waf\Tests\Routing\NamespaceRouterTest'],
                ['/FooBar/',   '/',      'Emonkak\Waf\Tests\Routing\NamespaceRouterTest'],
                ['/FOOBAR/',   '/',      'Emonkak\Waf\Tests\Routing\NamespaceRouterTest'],
                ['/hoge/foo/', '/hoge/', 'Emonkak\Waf\Tests\Routing\NamespaceRouterTest\Hoge'],
                ['/hoge/Bar/', '/hoge/', 'Emonkak\Waf\Tests\Routing\NamespaceRouterTest\Hoge'],
                ['/hoge/BAR/', '/hoge/', 'Emonkak\Waf\Tests\Routing\NamespaceRouterTest\Hoge'],
            ];
        }

        /**
         * @dataProvider provideMatchThrowsHttpRedirectException
         * @expectedException Emonkak\Waf\Exception\HttpRedirectException
         */
        public function testMatchThrowsHttpRedirectException($url, $prefix, $namespace, $expectedLocation)
        {
            $request = Request::create($url);
            $router = new NamespaceRouter($prefix, $namespace);

            try {
                $router->match($request);
            } catch (HttpRedirectException $e) {
                $this->assertSame(301, $e->getStatusCode());
                $this->assertSame(['Location' => $expectedLocation], $e->getHeaders());
                throw $e;
            }
        }

        public function provideMatchThrowsHttpRedirectException()
        {
            return [
                ['/index',       '/',      'Emonkak\Waf\Tests\Routing\NamespaceRouterTest',      '/index/'],
                ['/foo',         '/',      'Emonkak\Waf\Tests\Routing\NamespaceRouterTest',      '/foo/'],
                ['/foo?bar=baz', '/',      'Emonkak\Waf\Tests\Routing\NamespaceRouterTest',      '/foo/?bar=baz'],
                ['/foo_bar',     '/',      'Emonkak\Waf\Tests\Routing\NamespaceRouterTest',      '/foo_bar/'],
                ['/bar',         '/',      'Emonkak\Waf\Tests\Routing\NamespaceRouterTest',      '/bar/'],
                ['/hoge',        '/hoge/', 'Emonkak\Waf\Tests\Routing\NamespaceRouterTest\Hoge', '/hoge/'],
                ['/hoge/index',  '/hoge/', 'Emonkak\Waf\Tests\Routing\NamespaceRouterTest\Hoge', '/hoge/index/'],
                ['/hoge/foo',    '/hoge/', 'Emonkak\Waf\Tests\Routing\NamespaceRouterTest\Hoge', '/hoge/foo/'],
                ['/hoge/bar',    '/hoge/', 'Emonkak\Waf\Tests\Routing\NamespaceRouterTest\Hoge', '/hoge/bar/'],
            ];
        }

        /**
         * @dataProvider provideMatchReturnsNull
         */
        public function testMatchReturnsNull($url, $prefix, $namespace)
        {
            $request = Request::create($url);
            $router = new NamespaceRouter($prefix, $namespace);
            $this->assertNull($router->match($request));
        }

        public function provideMatchReturnsNull()
        {
            return [
                ['/',      '/hoge/', 'Emonkak\Waf\Tests\Routing\NamespaceRouterTest'],
                ['/HOGE',  '/hoge/', 'Emonkak\Waf\Tests\Routing\NamespaceRouterTest\Hoge'],
                ['/HOGE/', '/hoge/', 'Emonkak\Waf\Tests\Routing\NamespaceRouterTest\Hoge'],
                ['/huga',  '/hoge/', 'Emonkak\Waf\Tests\Routing\NamespaceRouterTest\Hoge'],
            ];
        }

        /**
         * @dataProvider provideGetPattern
         */
        public function testGetPattern($prefix, $namespace, $expectedPattern)
        {
            $router = new NamespaceRouter($prefix, $namespace);
            $this->assertSame($expectedPattern, $router->getPattern());
        }

        public function provideGetPattern()
        {
            return [
                ['/foo/', 'Emonkak\Waf\Tests\Routing\NamespaceRouterTest', '/foo/'],
                ['/foo/.*/', 'Emonkak\Waf\Tests\Routing\NamespaceRouterTest', '/foo/\\.\\*/'],
            ];
        }
    }
}

namespace Emonkak\Waf\Tests\Routing\NamespaceRouterTest
{
    class IndexController
    {
    }

    class FooController
    {
    }

    class FooBarController
    {
    }
}

namespace Emonkak\Waf\Tests\Routing\NamespaceRouterTest\Hoge
{
    class IndexController
    {
    }

    class BarController
    {
    }
}
