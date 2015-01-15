<?php

namespace Emonkak\Framework\Tests\Routing;

use Emonkak\Framework\Routing\NamespaceRouter;
use Emonkak\Framework\Routing\PatternRouter;
use Emonkak\Framework\Routing\RequestMatcherRouter;
use Emonkak\Framework\Routing\ResourceRouter;
use Emonkak\Framework\Routing\RouterBuilder;
use Symfony\Component\HttpFoundation\RequestMatcher;

class RouterBuilderTest extends \PHPUnit_Framework_TestCase
{
    public function testAdd()
    {
        $builder = new RouterBuilder();
        $builder
            ->add($mockRouter1 = $this->getMock('Emonkak\Framework\Routing\RouterInterface'))
            ->add($mockRouter2 = $this->getMock('Emonkak\Framework\Routing\RouterInterface'));

        $routers = iterator_to_array($builder->build());
        $this->assertSame([$mockRouter1, $mockRouter2], $routers);
    }

    public function testGet()
    {
        $pattern ='/foo/';
        $controller = 'Controller\FooController';
        $action = 'index';

        $builder = new RouterBuilder();
        $builder->get($pattern, $controller, $action);

        $routers = iterator_to_array($builder->build());
        $this->assertEquals(
            [
                new RequestMatcherRouter(
                    new PatternRouter($pattern, $controller, $action),
                    new RequestMatcher(null, null, 'GET')
                )
            ],
            $routers
        );
    }

    public function testPost()
    {
        $pattern ='/foo/create';
        $controller = 'Controller\FooController';
        $action = 'create';

        $builder = new RouterBuilder();
        $builder->post($pattern, $controller, $action);

        $routers = iterator_to_array($builder->build());
        $this->assertEquals(
            [
                new RequestMatcherRouter(
                    new PatternRouter($pattern, $controller, $action),
                    new RequestMatcher(null, null, 'POST')
                )
            ],
            $routers
        );
    }

    public function testPatch()
    {
        $pattern ='/foo/update/(\d+)';
        $controller = 'Controller\FooController';
        $action = 'update';

        $builder = new RouterBuilder();
        $builder->patch($pattern, $controller, $action);

        $routers = iterator_to_array($builder->build());
        $this->assertEquals(
            [
                new RequestMatcherRouter(
                    new PatternRouter($pattern, $controller, $action),
                    new RequestMatcher(null, null, 'PATCH')
                )
            ],
            $routers
        );
    }

    public function testPut()
    {
        $pattern ='/foo/update/(\d+)';
        $controller = 'Controller\FooController';
        $action = 'update';

        $builder = new RouterBuilder();
        $builder->put($pattern, $controller, $action);

        $routers = iterator_to_array($builder->build());
        $this->assertEquals(
            [
                new RequestMatcherRouter(
                    new PatternRouter($pattern, $controller, $action),
                    new RequestMatcher(null, null, 'PUT')
                )
            ],
            $routers
        );
    }

    public function testDelete()
    {
        $pattern ='/foo/delete/(\d+)';
        $controller = 'Controller\FooController';
        $action = 'delete';

        $builder = new RouterBuilder();
        $builder->delete($pattern, $controller, $action);

        $routers = iterator_to_array($builder->build());
        $this->assertEquals(
            [
                new RequestMatcherRouter(
                    new PatternRouter($pattern, $controller, $action),
                    new RequestMatcher(null, null, 'DELETE')
                )
            ],
            $routers
        );
    }

    public function testRegexp()
    {
        $pattern ='/foo/edit/(\d+)';
        $controller = 'Controller\FooController';
        $action = 'edit';

        $builder = new RouterBuilder();
        $builder->pattern($pattern, $controller, $action);

        $routers = iterator_to_array($builder->build());
        $this->assertEquals(
            [
                new PatternRouter($pattern, $controller, $action),
            ],
            $routers
        );
    }

    public function testResource()
    {
        $builder = new RouterBuilder();
        $builder->resource('/foo/', 'Controller\FooController');

        $routers = iterator_to_array($builder->build());
        $this->assertEquals(
            [
                new ResourceRouter('/foo/', 'Controller\FooController'),
            ],
            $routers
        );
    }

    public function testMount()
    {
        $builder = new RouterBuilder();
        $builder->mount('/', 'Controller');

        $routers = iterator_to_array($builder->build());
        $this->assertEquals(
            [
                new NamespaceRouter('/', 'Controller'),
            ],
            $routers
        );
    }

    public function testBuild()
    {
        $builder = new RouterBuilder();
        $router = $builder->build();

        $this->assertInstanceOf('Emonkak\Framework\Routing\RouterCollection', $router);
    }

    public function testOptimized()
    {
        $builder = new RouterBuilder();
        $router = $builder->optimized();

        $this->assertInstanceOf('Emonkak\Framework\Routing\OptimizedRouterCollection', $router);
    }
}
