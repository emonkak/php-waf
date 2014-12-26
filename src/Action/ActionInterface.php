<?php

namespace Emonkak\Framework\Action;

use Emonkak\Framework\InstantiatorInterface;
use Symfony\Component\HttpFoundation\Request;

interface ActionInterface
{
    /**
     * Calls the action method of the given controller.
     *
     * @param mixed $controller The controller instance
     * @return mixed[]
     */
    public function call($controller);

    /**
     * Returns whether the action can call.
     *
     * @return boolean
     */
    public function canCall();

    /**
     * Instantiates the controller to handle this action.
     *
     * @param InstantiatorInterface $instantiator
     * @return mixed
     */
    public function instantiateBy(InstantiatorInterface $instantiator);

    /**
     * For debug.
     *
     * @return string
     */
    public function __toString();
}
