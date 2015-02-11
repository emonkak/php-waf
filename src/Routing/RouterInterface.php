<?php

namespace Emonkak\Waf\Routing;

use Emonkak\Waf\Exception\HttpException;
use Psr\Http\Message\RequestInterface;

/**
 * Represents the matching of request to routes.
 */
interface RouterInterface
{
    const PATTERN_DELIMITER = '#';

    /**
     * Attemps to match the given request to this route.
     *
     * @param RequestInterface $request The request to match.
     * @return MatchesRoute
     * @throws HttpException
     */
    public function match(RequestInterface $request);

    /**
     * Gets the regexp pattern that matches this routing for optimization.
     *
     * @return string
     */
    public function getPattern();
}
