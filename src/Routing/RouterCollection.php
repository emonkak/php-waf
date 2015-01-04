<?php

namespace Emonkak\Framework\Routing;

use Symfony\Component\HttpFoundation\Request;

class RouterCollection implements RouterInterface
{
    /**
     * @var RouterInterface[]
     */
    private $routers = [];

    public static function from(array $routers)
    {
        $routerCollection = new RouterCollection();
        $routerCollection->addAll($routers);
        return $routerCollection;
    }

    /**
     * @param RouterInterface $router
     */
    public function add(RouterInterface $router)
    {
        $this->routers[] = $router;
    }

    /**
     * @param RouterInterface[] $routers
     */
    public function addAll(array $routers)
    {
        foreach ($routers as $router) {
            $this->add($router);
        }
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
