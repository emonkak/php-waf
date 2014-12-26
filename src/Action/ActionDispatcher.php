<?php

namespace Emonkak\Framework\Action;

use Emonkak\Framework\Exception\HttpNotFoundException;
use Symfony\Component\HttpFoundation\Request;

class ActionDispatcher implements ActionDispatcherInterface
{
    /**
     * {@inheritDoc}
     */
    public function dispatch(Request $request, ActionInterface $action, $controller)
    {
        if (!$action->canCall()) {
            throw new HttpNotFoundException(
                sprintf('Controller action "%s" can not be called.', $action)
            );
        }

        return $action->call($controller);
    }
}
