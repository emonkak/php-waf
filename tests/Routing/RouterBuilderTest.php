<?php

namespace Emonkak\Framework\Tests\Routing;

use Emonkak\Framework\Routing\NamespaceRouter;
use Emonkak\Framework\Routing\RegexpRouter;
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
        $builder = new RouterBuilder();
        $builder->get('|^/foo/|', 'Controller\FooController', 'index');

        $routers = iterator_to_array($builder->build());
        $this->assertEquals(
            [
                new RequestMatcherRouter(
                    new RegexpRouter('|^/foo/|', 'Controller\FooController', 'index'),
                    new RequestMatcher(null, null, 'GET')
                )
            ],
            $routers
        );
    }

    public function testPost()
    {
        $builder = new RouterBuilder();
        $builder->post('|^/foo/create|', 'Controller\FooController', 'create');

        $routers = iterator_to_array($builder->build());
        $this->assertEquals(
            [
                new RequestMatcherRouter(
                    new RegexpRouter('|^/foo/create|', 'Controller\FooController', 'create'),
                    new RequestMatcher(null, null, 'POST')
                )
            ],
            $routers
        );
    }

    public function testPut()
    {
        $builder = new RouterBuilder();
        $builder->put('|^/foo/update/(\d+)|', 'Controller\FooController', 'update');

        $routers = iterator_to_array($builder->build());
        $this->assertEquals(
            [
                new RequestMatcherRouter(
                    new RegexpRouter('|^/foo/update/(\d+)|', 'Controller\FooController', 'update'),
                    new RequestMatcher(null, null, 'PUT')
                )
            ],
            $routers
        );
    }

    public function testDelete()
    {
        $builder = new RouterBuilder();
        $builder->delete('|^/foo/delete/(\d+)|', 'Controller\FooController', 'delete');

        $routers = iterator_to_array($builder->build());
        $this->assertEquals(
            [
                new RequestMatcherRouter(
                    new RegexpRouter('|^/foo/delete/(\d+)|', 'Controller\FooController', 'delete'),
                    new RequestMatcher(null, null, 'DELETE')
                )
            ],
            $routers
        );
    }

    public function testRegexp()
    {
        $builder = new RouterBuilder();
        $builder->regexp('|^/foo/edit/(\d+)|', 'Controller\FooController', 'edit');

        $routers = iterator_to_array($builder->build());
        $this->assertEquals(
            [
                new RegexpRouter('|^/foo/edit/(\d+)|', 'Controller\FooController', 'edit'),
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
}
