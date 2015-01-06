<?php

namespace Emonkak\Framework\Routing;

use Symfony\Component\HttpFoundation\Request;

/**
 * Provides a routing from a regular expression.
 */
class RegexpRouter implements RouterInterface
{
    private $pattern;
    private $controller;
    private $action;

    /**
     * @param string $pattern    The regexp pattern for a request path.
     * @param string $controller The fully qualified class name of the controller.
     * @param string $action     The action name.
     */
    public function __construct($pattern, $controller, $action)
    {
        $this->pattern = $pattern;
        $this->controller = $controller;
        $this->action = $action;
    }

    /**
     * {@inheritDoc}
     */
    public function match(Request $request)
    {
        $path = $request->getPathInfo();
        $length = preg_match($this->pattern, $path, $matches);

        if ($length > 0) {
            $controller = new \ReflectionClass($this->controller);
            $action = $this->action;
            $params = array_slice($matches, 1);
            return new MatchedRoute($controller, $action, $params);
        }

        return null;
    }
}
