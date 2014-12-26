<?php

namespace Emonkak\Framework\Routing;

use Emonkak\Framework\Action\ControllerAction;
use Symfony\Component\HttpFoundation\Request;

class StaticRegexpRouter implements RouterInterface
{
    private $regexp;
    private $controller;
    private $action;

    public function __construct($regexp, $controller, $action)
    {
        $this->regexp = $regexp;
        $this->controller = $controller;
        $this->action = $action;
    }

    /**
     * {@inheritDoc}
     */
    public function match(Request $request)
    {
        $path = $request->getPathInfo();
        $length = preg_match($this->regexp, $path, $matches);

        if ($length > 0) {
            $controller = new \ReflectionClass($this->controller);
            $action = $controller->getMethod($this->action);
            $args = array_slice($matches, 1);
            return new ControllerAction($controller, $action, $args);
        }

        return null;
    }
}
