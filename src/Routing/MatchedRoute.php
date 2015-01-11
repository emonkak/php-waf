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
     * @param string  $controller
     * @param string  $action
     * @param mixed[] $params
     */
    public function __construct($controller, $action, array $params)
    {
        $this->controller = $controller;
        $this->action = $action;
        $this->params = $params;
    }
}
