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
     * @param string $controller
     * @param string $action
     * @param array  $params
     * @return MatchedRoute
     */
    public static function of($controller, $action, array $params)
    {
        return new self(new \ReflectionClass($controller), $action, $params);
    }

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
