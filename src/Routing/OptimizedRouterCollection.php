<?php

namespace Emonkak\Waf\Routing;

use Psr\Http\Message\RequestInterface;

/**
 * Provides a composition of routers that optimized performance by
 * pre-compilation of regular expression.
 */
class OptimizedRouterCollection extends RouterCollection
{
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
        parent::__construct($routers);

        $this->pattern = parent::getPattern();
    }

    /**
     * {@inheritDoc}
     */
    public function match(RequestInterface $request)
    {
        $pattern = self::PATTERN_DELIMITER . $this->pattern . self::PATTERN_DELIMITER . 'A';
        $path = $request->getUri()->getPath();

        preg_match($pattern, $path, $matches);

        // Should ignore $matches[0]
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
}
