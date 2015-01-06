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
                ['/',                         '/',      'Emonkak\Framework\Tests\Routing\NamespaceRouterTest',      'Emonkak\Framework\Tests\Routing\NamespaceRouterTest\IndexController', 'index', []],
                ['/index/',                   '/',      'Emonkak\Framework\Tests\Routing\NamespaceRouterTest',      'Emonkak\Framework\Tests\Routing\NamespaceRouterTest\IndexController', 'index', []],
                ['/index/index',              '/',      'Emonkak\Framework\Tests\Routing\NamespaceRouterTest',      'Emonkak\Framework\Tests\Routing\NamespaceRouterTest\IndexController', 'index', []],
                ['/foo/',                     '/',      'Emonkak\Framework\Tests\Routing\NamespaceRouterTest',      'Emonkak\Framework\Tests\Routing\NamespaceRouterTest\FooController', 'index', []],
                ['/foo/index',                '/',      'Emonkak\Framework\Tests\Routing\NamespaceRouterTest',      'Emonkak\Framework\Tests\Routing\NamespaceRouterTest\FooController', 'index', []],
                ['/foo/show/',                '/',      'Emonkak\Framework\Tests\Routing\NamespaceRouterTest',      'Emonkak\Framework\Tests\Routing\NamespaceRouterTest\FooController', 'show', ['']],
                ['/foo/show/123',             '/',      'Emonkak\Framework\Tests\Routing\NamespaceRouterTest',      'Emonkak\Framework\Tests\Routing\NamespaceRouterTest\FooController', 'show', ['123']],
                ['/foo/between/123/456',      '/',      'Emonkak\Framework\Tests\Routing\NamespaceRouterTest',      'Emonkak\Framework\Tests\Routing\NamespaceRouterTest\FooController', 'between', ['123', '456']],
                ['/foo_bar/',                 '/',      'Emonkak\Framework\Tests\Routing\NamespaceRouterTest',      'Emonkak\Framework\Tests\Routing\NamespaceRouterTest\FooBarController', 'index', []],
                ['/hoge/',                    '/hoge/', 'Emonkak\Framework\Tests\Routing\NamespaceRouterTest\Hoge', 'Emonkak\Framework\Tests\Routing\NamespaceRouterTest\Hoge\IndexController', 'index', []],
                ['/hoge/index/',              '/hoge/', 'Emonkak\Framework\Tests\Routing\NamespaceRouterTest\Hoge', 'Emonkak\Framework\Tests\Routing\NamespaceRouterTest\Hoge\IndexController', 'index', []],
                ['/hoge/index/index',         '/hoge/', 'Emonkak\Framework\Tests\Routing\NamespaceRouterTest\Hoge', 'Emonkak\Framework\Tests\Routing\NamespaceRouterTest\Hoge\IndexController', 'index', []],
                ['/hoge/bar/show/',           '/hoge/', 'Emonkak\Framework\Tests\Routing\NamespaceRouterTest\Hoge', 'Emonkak\Framework\Tests\Routing\NamespaceRouterTest\Hoge\BarController', 'show', ['']],
                ['/hoge/bar/show/123',        '/hoge/', 'Emonkak\Framework\Tests\Routing\NamespaceRouterTest\Hoge', 'Emonkak\Framework\Tests\Routing\NamespaceRouterTest\Hoge\BarController', 'show', ['123']],
                ['/hoge/bar/between/123/456', '/hoge/', 'Emonkak\Framework\Tests\Routing\NamespaceRouterTest\Hoge', 'Emonkak\Framework\Tests\Routing\NamespaceRouterTest\Hoge\BarController', 'between', ['123', '456']],
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
                ['/Foo/',      '/',      'Emonkak\Framework\Tests\Routing\NamespaceRouterTest'],
                ['/FOO/',      '/',      'Emonkak\Framework\Tests\Routing\NamespaceRouterTest'],
                ['/bar/',      '/',      'Emonkak\Framework\Tests\Routing\NamespaceRouterTest'],
                ['/Foo_Bar/',  '/',      'Emonkak\Framework\Tests\Routing\NamespaceRouterTest'],
                ['/FOO_BAR/',  '/',      'Emonkak\Framework\Tests\Routing\NamespaceRouterTest'],
                ['/FooBar/',   '/',      'Emonkak\Framework\Tests\Routing\NamespaceRouterTest'],
                ['/FOOBAR/',   '/',      'Emonkak\Framework\Tests\Routing\NamespaceRouterTest'],
                ['/hoge/foo/', '/hoge/', 'Emonkak\Framework\Tests\Routing\NamespaceRouterTest\Hoge'],
                ['/hoge/Bar/', '/hoge/', 'Emonkak\Framework\Tests\Routing\NamespaceRouterTest\Hoge'],
                ['/hoge/BAR/', '/hoge/', 'Emonkak\Framework\Tests\Routing\NamespaceRouterTest\Hoge'],
            ];
        }

        /**
         * @dataProvider provideMatchThrowsHttpRedirectException
         * @expectedException Emonkak\Framework\Exception\HttpRedirectException
         */
        public function testMatchThrowsHttpRedirectException($path, $prefix, $namespace, $expectedLocation)
        {
            $request = $this->getMock('Symfony\Component\HttpFoundation\Request');
            $request
                ->expects($this->once())
                ->method('getPathInfo')
                ->willReturn($path);

            $router = new NamespaceRouter($prefix, $namespace);

            try {
                $router->match($request);
            } catch (HttpRedirectException $e) {
                $this->assertSame(['Location' => $expectedLocation], $e->getHeaders());

                throw $e;
            }
        }

        public function provideMatchThrowsHttpRedirectException()
        {
            return [
                ['/index',      '/',      'Emonkak\Framework\Tests\Routing\NamespaceRouterTest',      '/index/'],
                ['/foo',        '/',      'Emonkak\Framework\Tests\Routing\NamespaceRouterTest',      '/foo/'],
                ['/foo_bar',    '/',      'Emonkak\Framework\Tests\Routing\NamespaceRouterTest',      '/foo_bar/'],
                ['/bar',        '/',      'Emonkak\Framework\Tests\Routing\NamespaceRouterTest',      '/bar/'],
                ['/hoge',       '/hoge/', 'Emonkak\Framework\Tests\Routing\NamespaceRouterTest\Hoge', '/hoge/'],
                ['/hoge/index', '/hoge/', 'Emonkak\Framework\Tests\Routing\NamespaceRouterTest\Hoge', '/hoge/index/'],
                ['/hoge/foo',   '/hoge/', 'Emonkak\Framework\Tests\Routing\NamespaceRouterTest\Hoge', '/hoge/foo/'],
                ['/hoge/bar',   '/hoge/', 'Emonkak\Framework\Tests\Routing\NamespaceRouterTest\Hoge', '/hoge/bar/'],
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
                ['/',      '/hoge/', 'Emonkak\Framework\Tests\Routing\NamespaceRouterTest'],
                ['/HOGE',  '/hoge/', 'Emonkak\Framework\Tests\Routing\NamespaceRouterTest\Hoge'],
                ['/HOGE/', '/hoge/', 'Emonkak\Framework\Tests\Routing\NamespaceRouterTest\Hoge'],
                ['/huga',  '/hoge/', 'Emonkak\Framework\Tests\Routing\NamespaceRouterTest\Hoge'],
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

namespace Emonkak\Framework\Tests\Routing\NamespaceRouterTest\Hoge
{
    class IndexController
    {
    }

    class BarController
    {
    }
}
