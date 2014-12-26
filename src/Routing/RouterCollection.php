<?php

namespace Emonkak\Framework\Routing;

use Symfony\Component\HttpFoundation\Request;

class RouterCollection implements RouterInterface
{
    /**
     * @var RouterInterface[]
     */
    private $routers;

    /**
     * @param RouterInterface[] $routers
     */
    public function __construct(array $routers = [])
    {
        $this->routers = $routers;
    }

    /**
     * @param RouterInterface $router
     */
    public function add(RouterInterface $router)
    {
        $this->routers[] = $router;
    }

    /**
     * {@inheritDoc}
     */
    public function match(Request $request)
    {
        foreach ($this->routers as $router) {
            $action = $router->match($request);

            if ($action) {
                return $action;
            }
        }

        return null;
    }
}
