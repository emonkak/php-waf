<?php

namespace Emonkak\Framework\Routing;

/**
 * Represents a matched route.
 */
class MatchedRoute
{
    public $controller;
    public $action;
    public $params;

    /**
     * @param \ReflectionClass $controller
     * @param string           $action
     * @param mixed[]          $params
     */
    public function __construct(\ReflectionClass $controller, $action, array $params)
    {
        $this->controller = $controller;
        $this->action = $action;
        $this->params = $params;
    }
}
