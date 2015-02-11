<?php

namespace Emonkak\Waf\Action;

use Emonkak\Waf\Routing\MatchedRoute;
use Psr\Http\Message\RequestInterface;

class ActionDispatcherCollection implements ActionDispatcherInterface, \IteratorAggregate
{
    /**
     * @var ActionDispatcherInterface[]
     */
    private $dispatchers;

    /**
     * @param ActionDispatcherInterface[] $dispatchers
     */
    public function __construct(array $dispatchers)
    {
        $this->dispatchers = $dispatchers;
    }

    /**
     * {@inheritDoc}
     */
    public function dispatch(RequestInterface $request, MatchedRoute $match, $controller)
    {
        foreach ($this->dispatchers as $dispatcher) {
            if ($dispatcher->canDispatch($request, $match, $controller)) {
                return $dispatcher->dispatch($request, $match, $controller);
            }
        }
        throw new \LogicException('Can not dispatch the request.');
    }

    /**
     * {@inheritDoc}
     */
    public function canDispatch(RequestInterface $request, MatchedRoute $match, $controller)
    {
        foreach ($this->dispatchers as $dispatcher) {
            if ($dispatcher->canDispatch($request, $match, $controller)) {
                return true;
            }
        }
        return false;
    }

    /**
     * @see \IteratorAggregate
     * @return \Iterator
     */
    public function getIterator()
    {
        return new \ArrayIterator($this->dispatchers);
    }
}
