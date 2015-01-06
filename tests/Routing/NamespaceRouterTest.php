<?php

namespace Emonkak\Framework\Tests\Routing
{
    use Emonkak\Framework\Exception\HttpNotFoundException;
    use Emonkak\Framework\Exception\HttpRedirectException;
    use Emonkak\Framework\Routing\NamespaceRouter;
    use Symfony\Component\HttpFoundation\Request;

    class NamespaceRouterTest extends \PHPUnit_Framework_TestCase
    {
        /**
         * @dataProvider provideMatch
         */
        public function testMatch($path, $prefix, $namespace, $expectedController, $expectedAction, $expectedParams)
        {
            $request = $this->getMock('Symfony\Component\HttpFoundation\Request');
            $request
                ->expects($this->once())
                ->method('getPathInfo')
                ->willReturn($path);

            $router = new NamespaceRouter($prefix, $namespace);
            $match = $router->match($request);

            $this->assertNotNull($match);
            $this->assertSame($expectedController, $match->controller->getName());
            $this->assertSame($expectedAction, $match->action);
            $this->assertSame($expectedParams, $match->params);
        }

        public function provideMatch()
        {
            return [
                ['/',                        '/',     'Emonkak\Framework\Tests\Routing\NamespaceRouterTest',     'Emonkak\Framework\Tests\Routing\NamespaceRouterTest\IndexController', 'index', []],
                ['/index/',                  '/',     'Emonkak\Framework\Tests\Routing\NamespaceRouterTest',     'Emonkak\Framework\Tests\Routing\NamespaceRouterTest\IndexController', 'index', []],
                ['/index/index',             '/',     'Emonkak\Framework\Tests\Routing\NamespaceRouterTest',     'Emonkak\Framework\Tests\Routing\NamespaceRouterTest\IndexController', 'index', []],
                ['/foo/',                    '/',     'Emonkak\Framework\Tests\Routing\NamespaceRouterTest',     'Emonkak\Framework\Tests\Routing\NamespaceRouterTest\FooController', 'index', []],
                ['/foo/index',               '/',     'Emonkak\Framework\Tests\Routing\NamespaceRouterTest',     'Emonkak\Framework\Tests\Routing\NamespaceRouterTest\FooController', 'index', []],
                ['/foo/show/',               '/',     'Emonkak\Framework\Tests\Routing\NamespaceRouterTest',     'Emonkak\Framework\Tests\Routing\NamespaceRouterTest\FooController', 'show', ['']],
                ['/foo/show/123',            '/',     'Emonkak\Framework\Tests\Routing\NamespaceRouterTest',     'Emonkak\Framework\Tests\Routing\NamespaceRouterTest\FooController', 'show', ['123']],
                ['/foo/between/123/456',     '/',     'Emonkak\Framework\Tests\Routing\NamespaceRouterTest',     'Emonkak\Framework\Tests\Routing\NamespaceRouterTest\FooController', 'between', ['123', '456']],
                ['/foo_bar/',                '/',     'Emonkak\Framework\Tests\Routing\NamespaceRouterTest',     'Emonkak\Framework\Tests\Routing\NamespaceRouterTest\FooBarController', 'index', []],
                ['/foo/',                    '/foo/', 'Emonkak\Framework\Tests\Routing\NamespaceRouterTest\Foo', 'Emonkak\Framework\Tests\Routing\NamespaceRouterTest\Foo\IndexController', 'index', []],
                ['/foo/index/',              '/foo/', 'Emonkak\Framework\Tests\Routing\NamespaceRouterTest\Foo', 'Emonkak\Framework\Tests\Routing\NamespaceRouterTest\Foo\IndexController', 'index', []],
                ['/foo/index/index',         '/foo/', 'Emonkak\Framework\Tests\Routing\NamespaceRouterTest\Foo', 'Emonkak\Framework\Tests\Routing\NamespaceRouterTest\Foo\IndexController', 'index', []],
                ['/foo/bar/show/',           '/foo/', 'Emonkak\Framework\Tests\Routing\NamespaceRouterTest\Foo', 'Emonkak\Framework\Tests\Routing\NamespaceRouterTest\Foo\BarController', 'show', ['']],
                ['/foo/bar/show/123',        '/foo/', 'Emonkak\Framework\Tests\Routing\NamespaceRouterTest\Foo', 'Emonkak\Framework\Tests\Routing\NamespaceRouterTest\Foo\BarController', 'show', ['123']],
                ['/foo/bar/between/123/456', '/foo/', 'Emonkak\Framework\Tests\Routing\NamespaceRouterTest\Foo', 'Emonkak\Framework\Tests\Routing\NamespaceRouterTest\Foo\BarController', 'between', ['123', '456']],
            ];
        }

        /**
         * @dataProvider provideMatchThrowsHttpNotFoundException
         * @expectedException Emonkak\Framework\Exception\HttpNotFoundException
         */
        public function testMatchThrowsHttpNotFoundException($path, $prefix, $namespace)
        {
            $request = $this->getMock('Symfony\Component\HttpFoundation\Request');
            $request
                ->expects($this->once())
                ->method('getPathInfo')
                ->willReturn($path);

            $router = new NamespaceRouter($prefix, $namespace);
            $router->match($request);
        }

        public function provideMatchThrowsHttpNotFoundException()
        {
            return [
                ['/index',     '/',     'Emonkak\Framework\Tests\Routing\NamespaceRouterTest',],
                ['/bar',       '/',     'Emonkak\Framework\Tests\Routing\NamespaceRouterTest'],
                ['/bar/',      '/',     'Emonkak\Framework\Tests\Routing\NamespaceRouterTest'],
                ['/foo',       '/',     'Emonkak\Framework\Tests\Routing\NamespaceRouterTest',],
                ['/foo_bar',   '/',     'Emonkak\Framework\Tests\Routing\NamespaceRouterTest',],
                ['/foo/bar',   '/foo/', 'Emonkak\Framework\Tests\Routing\NamespaceRouterTest\Foo'],
                ['/foo/foo',   '/foo/', 'Emonkak\Framework\Tests\Routing\NamespaceRouterTest\Foo'],
                ['/foo/foo/',  '/foo/', 'Emonkak\Framework\Tests\Routing\NamespaceRouterTest\Foo'],
                ['/foo/index', '/foo/', 'Emonkak\Framework\Tests\Routing\NamespaceRouterTest\Foo'],
            ];
        }

        /**
         * @dataProvider provideMatchReturnsNull
         */
        public function testMatchReturnsNull($path, $prefix, $namespace)
        {
            $request = $this->getMock('Symfony\Component\HttpFoundation\Request');
            $request
                ->expects($this->once())
                ->method('getPathInfo')
                ->willReturn($path);

            $router = new NamespaceRouter($prefix, $namespace);
            $this->assertNull($router->match($request));
        }

        public function provideMatchReturnsNull()
        {
            return [
                ['/',    '/foo/',     'Emonkak\Framework\Tests\Routing\NamespaceRouterTest'],
                ['/foo', '/foo/', 'Emonkak\Framework\Tests\Routing\NamespaceRouterTest\Foo'],
                ['/bar', '/foo/', 'Emonkak\Framework\Tests\Routing\NamespaceRouterTest\Foo'],
            ];
        }
    }
}

namespace Emonkak\Framework\Tests\Routing\NamespaceRouterTest
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

namespace Emonkak\Framework\Tests\Routing\NamespaceRouterTest\Foo
{
    class IndexController
    {
    }

    class BarController
    {
    }
}
