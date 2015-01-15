<?php

namespace Emonkak\Framework\Benchmarks;

use Athletic\AthleticEvent;
use Emonkak\Framework\Routing\NamespaceRouter;
use Emonkak\Framework\Routing\OptimizedRouterCollection;
use Emonkak\Framework\Routing\RouterCollection;
use Symfony\Component\HttpFoundation\Request;

class RouterEvent extends AthleticEvent
{
    private $request;
    private $routers;
    private $routerCollection;
    private $optimizedRouterCollection;

    public function setUp()
    {
        $this->request = Request::create('/thud/foo/bar/baz/qux/quux/corge/grauit/graply/waldo/fred/plugh/xyzzy/thud');

        $this->routers = [
            new NamespaceRouter('/foo/foo/foo/foo/', __NAMESPACE__),
            new NamespaceRouter('/bar/bar/bar/bar/', __NAMESPACE__),
            new NamespaceRouter('/baz/baz/baz/baz/', __NAMESPACE__),
            new NamespaceRouter('/qux/qux/qux/qux/', __NAMESPACE__),
            new NamespaceRouter('/quux/quux/quux/quux/', __NAMESPACE__),
            new NamespaceRouter('/corge/corge/corge/corge/', __NAMESPACE__),
            new NamespaceRouter('/grauit/grauit/grauit/grauit/', __NAMESPACE__),
            new NamespaceRouter('/graply/graply/graply/graply/', __NAMESPACE__),
            new NamespaceRouter('/waldo/waldo/waldo/waldo/', __NAMESPACE__),
            new NamespaceRouter('/fred/fred/fred/fred/', __NAMESPACE__),
            new NamespaceRouter('/plugh/plugh/plugh/plugh/', __NAMESPACE__),
            new NamespaceRouter('/xyzzy/xyzzy/xyzzy/xyzzy/', __NAMESPACE__),
            new NamespaceRouter('/thud/thud/thud/thud/', __NAMESPACE__),
            new NamespaceRouter('/foo/foo/foo/', __NAMESPACE__),
            new NamespaceRouter('/bar/bar/bar/', __NAMESPACE__),
            new NamespaceRouter('/baz/baz/baz/', __NAMESPACE__),
            new NamespaceRouter('/qux/qux/qux/', __NAMESPACE__),
            new NamespaceRouter('/quux/quux/quux/', __NAMESPACE__),
            new NamespaceRouter('/corge/corge/corge/', __NAMESPACE__),
            new NamespaceRouter('/grauit/grauit/grauit/', __NAMESPACE__),
            new NamespaceRouter('/graply/graply/graply/', __NAMESPACE__),
            new NamespaceRouter('/waldo/waldo/waldo/', __NAMESPACE__),
            new NamespaceRouter('/fred/fred/fred/', __NAMESPACE__),
            new NamespaceRouter('/plugh/plugh/plugh/', __NAMESPACE__),
            new NamespaceRouter('/xyzzy/xyzzy/xyzzy/', __NAMESPACE__),
            new NamespaceRouter('/thud/thud/thud/', __NAMESPACE__),
            new NamespaceRouter('/foo/foo/', __NAMESPACE__),
            new NamespaceRouter('/bar/bar/', __NAMESPACE__),
            new NamespaceRouter('/baz/baz/', __NAMESPACE__),
            new NamespaceRouter('/qux/qux/', __NAMESPACE__),
            new NamespaceRouter('/quux/quux/', __NAMESPACE__),
            new NamespaceRouter('/corge/corge/', __NAMESPACE__),
            new NamespaceRouter('/grauit/grauit/', __NAMESPACE__),
            new NamespaceRouter('/graply/graply/', __NAMESPACE__),
            new NamespaceRouter('/waldo/waldo/', __NAMESPACE__),
            new NamespaceRouter('/fred/fred/', __NAMESPACE__),
            new NamespaceRouter('/plugh/plugh/', __NAMESPACE__),
            new NamespaceRouter('/xyzzy/xyzzy/', __NAMESPACE__),
            new NamespaceRouter('/thud/thud/', __NAMESPACE__),
            new NamespaceRouter('/foo/', __NAMESPACE__),
            new NamespaceRouter('/bar/', __NAMESPACE__),
            new NamespaceRouter('/baz/', __NAMESPACE__),
            new NamespaceRouter('/qux/', __NAMESPACE__),
            new NamespaceRouter('/quux/', __NAMESPACE__),
            new NamespaceRouter('/corge/', __NAMESPACE__),
            new NamespaceRouter('/grauit/', __NAMESPACE__),
            new NamespaceRouter('/graply/', __NAMESPACE__),
            new NamespaceRouter('/waldo/', __NAMESPACE__),
            new NamespaceRouter('/fred/', __NAMESPACE__),
            new NamespaceRouter('/plugh/', __NAMESPACE__),
            new NamespaceRouter('/xyzzy/', __NAMESPACE__),
            new NamespaceRouter('/thud/', __NAMESPACE__),
        ];

        $this->routerCollection = new RouterCollection($this->routers);
        $this->optimizedRouterCollection = new OptimizedRouterCollection($this->routers);
    }

    /**
     * @iterations 1000
     */
    public function routerCollection()
    {
        $match = $this->routerCollection->match($this->request);
        assert($match !== null);
    }

    /**
     * @iterations 1000
     */
    public function optimizedRouterCollection()
    {
        $match = $this->optimizedRouterCollection->match($this->request); 
        assert($match !== null);
    }

    /**
     * @iterations 1000
     */
    public function optimizedRouterCollectionWithConstructor()
    {
        $optimizedRouterCollection = new OptimizedRouterCollection($this->routers);
        $match = $optimizedRouterCollection->match($this->request); 
        assert($match !== null);
    }
}

class FooController {}
