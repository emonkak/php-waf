<?php

namespace Emonkak\Framework\Routing;

use Emonkak\Framework\Utils\ReflectionUtils;
use Symfony\Component\HttpFoundation\Request;

class RegexpRouter implements RouterInterface
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
            $controller = $this->controller;
            $action = $this->action;
            $params = array_slice($matches, 1);
            return new MatchedRoute($controller, $action, $params);
        }

        return null;
    }
}
