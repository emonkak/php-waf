<?php

namespace Emonkak\Framework\Action;

use Emonkak\Framework\Routing\MatchedRoute;
use Symfony\Component\HttpFoundation\Request;

class ActionDispatcherCollection implements ActionDispatcherInterface, \IteratorAggregate
{
    private $dispatchers = [];

    /**
     * Create this instance from given dispatchers.
     *
     * @param ActionDispatcherInterface[] $dispatchers
     * @return ActionDispatcherCollection
     */
    public static function from(array $dispatchers = [])
    {
        $collection = new ActionDispatcherCollection();
        $collection->addAll($dispatchers);
        return $collection;
    }

    /**
     * Adds an action dispatcher to this collection.
     *
     * @param ActionDispatcherInterface $dispatcher
     * @return ActionDispatcherCollection
     */
    public function add(ActionDispatcherInterface $dispatcher)
    {
        $this->dispatchers[] = $dispatcher;
        return $this;
    }

    /**
     * Adds all action dispatcher to this collection.
     *
     * @param ActionDispatcherInterface[] $dispatchers
     * @return ActionDispatcherCollection
     */
    public function addAll(array $dispatchers)
    {
        foreach ($dispatchers as $dispatcher) {
            $this->add($dispatcher);
        }
        return $this;
    }

    /**
     * {@inheritDoc}
     */
    public function dispatch(Request $request, MatchedRoute $match, $controller)
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
    public function canDispatch(Request $request, MatchedRoute $match, $controller)
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
