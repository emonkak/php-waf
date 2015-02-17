<?php

namespace Emonkak\Waf\Routing;

/**
 * Represents a matched route.
 */
class MatchedRoute
{
    /**
     * @var string
     */
    public $controller;

    /**
     * @var string
     */
    public $action;

    /**
     * @var string[]
     */
    public $params;

    /**
     * @param string  $controller The controller class name.
     * @param string  $action     The action name.
     * @param string[] $params    The action parameters.
     */
    public function __construct($controller, $action, array $params)
    {
        $this->controller = $controller;
        $this->action = $action;
        $this->params = $params;
    }
}
