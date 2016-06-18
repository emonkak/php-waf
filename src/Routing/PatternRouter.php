<?php

namespace Emonkak\Waf\Routing;

use Symfony\Component\HttpFoundation\Request;

/**
 * Provides a routing from a regular expression.
 */
class PatternRouter implements RouterInterface
{
    /**
     * @var string
     */
    private $pattern;

    /**
     * @var string
     */
    private $controller;

    /**
     * @var string
     */
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
        $pattern = self::PATTERN_DELIMITER . $this->pattern . self::PATTERN_DELIMITER . 'AD';
        $length = preg_match($pattern, $path, $matches);

        if ($length > 0) {
            $action = $this->action;
            $params = array_slice($matches, 1);
            return new MatchedRoute($this->controller, $action, $params);
        }

        return null;
    }

    /**
     * {@inheritDoc}
     */
    public function getPattern()
    {
        return $this->pattern;
    }
}
