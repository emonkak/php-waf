<?php

namespace Emonkak\Framework\Action;

use Emonkak\Framework\InstantiatorInterface;
use Emonkak\Framework\Utils\ReflectionUtils;
use Symfony\Component\HttpFoundation\Request;

class ControllerAction implements ActionInterface
{
    private $controller;
    private $action;
    private $args;

    public function __construct(\ReflectionClass $controller, \ReflectionMethod $action, array $args)
    {
        $this->controller = $controller;
        $this->action = $action;
        $this->args = $args;
    }

    /**
     * {@inheritDoc}
     */
    public function call($controller)
    {
        return $this->action->invokeArgs($controller, $this->args);
    }

    /**
     * {@inheritDoc}
     */
    public function canCall()
    {
        return ReflectionUtils::matchesNumberOfArguments($this->action, count($this->args));
    }

    /**
     * {@inheritDoc}
     */
    public function instantiateBy(InstantiatorInterface $instantiator)
    {
        return $instantiator->instantiate($this->controller);
    }

    /**
     * {@inheritDoc}
     */
    public function __toString()
    {
        return sprintf('%s::%s()', $this->controller->getName(), $this->action->getName());
    }
}
