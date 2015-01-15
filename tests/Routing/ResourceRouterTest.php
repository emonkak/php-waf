<?php

namespace Emonkak\Framework\Tests\Routing
{
    use Emonkak\Framework\Exception\HttpNotFoundException;
    use Emonkak\Framework\Exception\HttpRedirectException;
    use Emonkak\Framework\Routing\ResourceRouter;
    use Symfony\Component\HttpFoundation\Request;

    class ResourceRouterTest extends \PHPUnit_Framework_TestCase
    {
        /**
         * @dataProvider provideMatch
         */
        public function testMatch($path, $prefix, $controller, $expectedAction, $expectedParams)
        {
            $request = Request::create($path);
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
                ['/',                    '/',     'Emonkak\Framework\Tests\Routing\ResourceRouterTest\FooController', 'index', []],
                ['/123',                 '/',     'Emonkak\Framework\Tests\Routing\ResourceRouterTest\FooController', 'show', ['123']],
                ['/123/',                '/',     'Emonkak\Framework\Tests\Routing\ResourceRouterTest\FooController', 'index', ['123']],
                ['/123/edit',            '/',     'Emonkak\Framework\Tests\Routing\ResourceRouterTest\FooController', 'edit', ['123']],
                ['/123/between/456',     '/',     'Emonkak\Framework\Tests\Routing\ResourceRouterTest\FooController', 'between', ['123', '456']],
                ['/foo/',                '/foo/', 'Emonkak\Framework\Tests\Routing\ResourceRouterTest\FooController', 'index', []],
                ['/foo/123',             '/foo/', 'Emonkak\Framework\Tests\Routing\ResourceRouterTest\FooController', 'show', ['123']],
                ['/foo/123/',            '/foo/', 'Emonkak\Framework\Tests\Routing\ResourceRouterTest\FooController', 'index', ['123']],
                ['/foo/123/edit',        '/foo/', 'Emonkak\Framework\Tests\Routing\ResourceRouterTest\FooController', 'edit', ['123']],
                ['/foo/123/between/456', '/foo/', 'Emonkak\Framework\Tests\Routing\ResourceRouterTest\FooController', 'between', ['123', '456']],
            ];
        }

        /**
         * @dataProvider provideMatchReturnsNull
         */
        public function testMatchReturnsNull($path, $prefix, $controller)
        {
            $request = Request::create($path);
            $router = new ResourceRouter($prefix, $controller);
            $this->assertNull($router->match($request));
        }

        public function provideMatchReturnsNull()
        {
            return [
                ['/',     '/foo/', 'Emonkak\Framework\Tests\Routing\ResourceRouterTest\FooController'],
                ['/FOO',  '/foo/', 'Emonkak\Framework\Tests\Routing\ResourceRouterTest\FooController'],
                ['/FOO/', '/foo/', 'Emonkak\Framework\Tests\Routing\ResourceRouterTest\FooController'],
                ['/bar/', '/foo/', 'Emonkak\Framework\Tests\Routing\ResourceRouterTest\FooController'],
            ];
        }

        /**
         * @expectedException Emonkak\Framework\Exception\HttpRedirectException
         * @dataProvider provideMatchHttpRedirectException
         */
        public function testMatchThrowsHttpRedirectException($path, $prefix, $controller, $expectedLocation)
        {
            $request = Request::create($path);
            $router = new ResourceRouter($prefix, $controller);

            try {
                $router->match($request);
            } catch (HttpRedirectException $e) {
                $this->assertSame(['Location' => $expectedLocation], $e->getHeaders());
                throw $e;
            }
        }

        public function provideMatchHttpRedirectException()
        {
            return [
                ['/foo',     '/foo/',     'Emonkak\Framework\Tests\Routing\ResourceRouterTest\FooController', '/foo/'],
                ['/foo/bar', '/foo/bar/', 'Emonkak\Framework\Tests\Routing\ResourceRouterTest\FooController', '/foo/bar/'],
            ];
        }
    }
}

namespace Emonkak\Framework\Tests\Routing\ResourceRouterTest
{
    class FooController
    {
    }
}
