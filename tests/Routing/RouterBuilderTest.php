<?php

namespace Emonkak\Waf\Tests\Routing;

use Emonkak\Waf\Routing\NamespaceRouter;
use Emonkak\Waf\Routing\PatternRouter;
use Emonkak\Waf\Routing\RequestMatcherRouter;
use Emonkak\Waf\Routing\ResourceRouter;
use Emonkak\Waf\Routing\RouterBuilder;
use Emonkak\Waf\Routing\RouterCollection;
use Symfony\Component\HttpFoundation\RequestMatcher;

class RouterBuilderTest extends \PHPUnit_Framework_TestCase
{
    public function testAdd()
    {
        $builder = new RouterBuilder();
        $builder
            ->add($mockRouter1 = $this->getMock('Emonkak\Waf\Routing\RouterInterface'))
            ->add($mockRouter2 = $this->getMock('Emonkak\Waf\Routing\RouterInterface'));

        $this->assertEquals(new RouterCollection([$mockRouter1, $mockRouter2]), $builder->build());
    }

    public function testGet()
    {
        $pattern ='/foo/';
        $controller = 'Controller\FooController';
        $action = 'index';

        $builder = new RouterBuilder();
        $builder->get($pattern, $controller, $action);

        $this->assertEquals(
            new RouterCollection([
                new RequestMatcherRouter(
                    new PatternRouter($pattern, $controller, $action),
                    new RequestMatcher(null, null, 'GET')
                )
            ]),
            $builder->build()
        );
    }

    public function testPost()
    {
        $pattern ='/foo/create';
        $controller = 'Controller\FooController';
        $action = 'create';

        $builder = new RouterBuilder();
        $builder->post($pattern, $controller, $action);

        $this->assertEquals(
            new RouterCollection([
                new RequestMatcherRouter(
                    new PatternRouter($pattern, $controller, $action),
                    new RequestMatcher(null, null, 'POST')
                )
            ]),
            $builder->build()
        );
    }

    public function testPatch()
    {
        $pattern ='/foo/update/(\d+)';
        $controller = 'Controller\FooController';
        $action = 'update';

        $builder = new RouterBuilder();
        $builder->patch($pattern, $controller, $action);

        $this->assertEquals(
            new RouterCollection([
                new RequestMatcherRouter(
                    new PatternRouter($pattern, $controller, $action),
                    new RequestMatcher(null, null, 'PATCH')
                )
            ]),
            $builder->build()
        );
    }

    public function testPut()
    {
        $pattern ='/foo/update/(\d+)';
        $controller = 'Controller\FooController';
        $action = 'update';

        $builder = new RouterBuilder();
        $builder->put($pattern, $controller, $action);

        $this->assertEquals(
            new RouterCollection([
                new RequestMatcherRouter(
                    new PatternRouter($pattern, $controller, $action),
                    new RequestMatcher(null, null, 'PUT')
                )
            ]),
            $builder->build()
        );
    }

    public function testDelete()
    {
        $pattern ='/foo/delete/(\d+)';
        $controller = 'Controller\FooController';
        $action = 'delete';

        $builder = new RouterBuilder();
        $builder->delete($pattern, $controller, $action);

        $this->assertEquals(
            new RouterCollection([
                new RequestMatcherRouter(
                    new PatternRouter($pattern, $controller, $action),
                    new RequestMatcher(null, null, 'DELETE')
                )
            ]),
            $builder->build()
        );
    }

    public function testRegexp()
    {
        $pattern ='/foo/edit/(\d+)';
        $controller = 'Controller\FooController';
        $action = 'edit';

        $builder = new RouterBuilder();
        $builder->pattern($pattern, $controller, $action);

        $this->assertEquals(
            new RouterCollection([
                new PatternRouter($pattern, $controller, $action),
            ]),
            $builder->build()
        );
    }

    public function testResource()
    {
        $builder = new RouterBuilder();
        $builder->resource('/foo/', 'Controller\FooController');

        $this->assertEquals(
            new RouterCollection([
                new ResourceRouter('/foo/', 'Controller\FooController'),
            ]),
            $builder->build()
        );
    }

    public function testMount()
    {
        $builder = new RouterBuilder();
        $builder->mount('/', 'Controller');

        $this->assertEquals(
            new RouterCollection([
                new NamespaceRouter('/', 'Controller'),
            ]),
            $builder->build()
        );
    }

    public function testBuild()
    {
        $builder = new RouterBuilder();
        $router = $builder->build();

        $this->assertInstanceOf('Emonkak\Waf\Routing\RouterCollection', $router);
    }

    public function testOptimized()
    {
        $builder = new RouterBuilder();
        $router = $builder->optimized();

        $this->assertInstanceOf('Emonkak\Waf\Routing\OptimizedRouterCollection', $router);
    }
}
