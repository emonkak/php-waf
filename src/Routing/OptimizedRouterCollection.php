<?php

namespace Emonkak\Waf\Routing;

use Symfony\Component\HttpFoundation\Request;

/**
 * Provides a composition of routers that optimized performance by
 * pre-compilation of regular expression.
 */
class OptimizedRouterCollection implements RouterInterface
{
    /**
     * @var RouterInterface[]
     */
    private $routers;

    /**
     * @var string
     */
    private $pattern;

    /**
     * Create this instance from given routers.
     *
     * @param RouterInterface[] $routers
     */
    public function __construct(array $routers)
    {
        $this->routers = $routers;
        $this->pattern = $this->preparePattern();
    }

    /**
     * {@inheritDoc}
     */
    public function match(Request $request)
    {
        $pattern = self::PATTERN_DELIMITER . $this->pattern . self::PATTERN_DELIMITER . 'AD';
        $path = $request->getPathInfo();

        preg_match($pattern, $path, $matches);

        $index = count($matches) - 2;

        if (isset($this->routers[$index])) {
            $router = $this->routers[$index];
            return $router->match($request);
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

    /**
     * @return string
     */
    protected function preparePattern()
    {
        $patterns = [];

        foreach ($this->routers as $i => $router) {
            $replaced = preg_replace('/(?<!\\\\)\((?!\?)/', '(?:', $router->getPattern());
            $patterns[] = '(' . $replaced . ')';
        }

        return implode('|', $patterns);
    }
}
