<?php

namespace Emonkak\Framework\Routing;

class MatchedRoute
{
    public $controller;
    public $action;
    public $params;

    public function __construct($controller, $action, $params)
    {
        $this->controller = $controller;
        $this->action = $action;
        $this->params = $params;
    }
}
