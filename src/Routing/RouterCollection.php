<?php

namespace Emonkak\Framework\Routing;

use Symfony\Component\HttpFoundation\Request;

/**
 * Provides a composition of routers.
 */
class RouterCollection implements RouterInterface, \IteratorAggregate
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
     * @see \IteratorAggregate
     * @return \Iterator
     */
    public function getIterator()
    {
        return new \ArrayIterator($this->routers);
    }
}
