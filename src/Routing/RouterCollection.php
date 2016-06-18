<?php

namespace Emonkak\Waf\Routing;

use Symfony\Component\HttpFoundation\Request;

/**
 * Provides a composition of routers.
 */
class RouterCollection implements RouterInterface
{
    /**
     * @var RouterInterface[]
     */
    private $routers;

    /**
     * Create this instance from given routers.
     *
     * @param RouterInterface[] $routers
     */
    public function __construct(array $routers)
    {
        $this->routers = $routers;
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

    /**
     * {@inheritDoc}
     */
    public function getPattern()
    {
        $patterns = [];

        foreach ($this->routers as $router) {
            $patterns[] = $router->getPattern();
        }

        return implode('|', $patterns);
    }
}
