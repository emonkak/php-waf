<?php

namespace Emonkak\Framework\Routing;

use Symfony\Component\HttpFoundation\Request;

/**
 * Provides a composition of routers.
 */
class RouterCollection implements RouterInterface
{
    private $routers = [];

    /**
     * Create this instance from given routers.
     *
     * @param RouterInterface[] $routers
     * @return RouterCollection
     */
    public static function from(array $routers)
    {
        $routerCollection = new RouterCollection();
        $routerCollection->addAll($routers);
        return $routerCollection;
    }

    /**
     * Adds a router to this collection.
     *
     * @param RouterInterface $router
     * @return RouterCollection
     */
    public function add(RouterInterface $router)
    {
        $this->routers[] = $router;
        return $this;
    }

    /**
     * Adds routers to this collection.
     *
     * @param RouterInterface[] $routers
     * @return RouterCollection
     */
    public function addAll(array $routers)
    {
        foreach ($routers as $router) {
            $this->add($router);
        }
        return $this;
    }

    /**
     * {@inheritDoc}
     */
    public function match(Request $request)
    {
        foreach ($this->routers as $router) {
            $match = $router->match($request);
            if ($match) {
                return $match;
            }
        }

        return null;
    }
}
