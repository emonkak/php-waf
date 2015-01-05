<?php

namespace Emonkak\Framework\Routing;

use Emonkak\Framework\Exception\HttpException;
use Symfony\Component\HttpFoundation\Request;

/**
 * Represents the matching of request to routes.
 */
interface RouterInterface
{
    /**
     * Attemps to match the given request to this route.
     *
     * @param Request $request The request to match.
     * @return MatchesRoute
     * @throws HttpException
     */
    public function match(Request $request);
}
