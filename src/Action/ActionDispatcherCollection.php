<?php

namespace Emonkak\Framework\Action;

use Emonkak\Framework\Exception\HttpNotFoundException;
use Emonkak\Framework\Routing\MatchedRoute;
use Symfony\Component\HttpFoundation\Request;

class ActionDispatcherCollection implements ActionDispatcherInterface
{
    /**
     * @var ActionDispatcherInterface[]
     */
    private $dispatchers;

    public function __construct(array $dispatchers = [])
    {
        $this->dispatchers[] = $dispatchers;
    }

    /**
     * Adds the action dispatcher to this collection.
     *
     * @param ActionDispatcherInterface $dispatcher
     */
    public function add(ActionDispatcherInterface $dispatcher)
    {
        $this->dispatchers[] = $dispatcher;
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
}
