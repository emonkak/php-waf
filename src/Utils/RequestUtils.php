<?php

namespace Emonkak\Waf\Utils;

use Symfony\Component\HttpFoundation\Request;

/**
 * Utilities for symfony request.
 */
class RequestUtils
{
    /**
     * @codeCoverageIgnore
     */
    private function __construct()
    {
    }

    /**
     * @param Request $request
     * @return string
     */
    public static function completeTrailingSlash(Request $request)
    {
        $queryString = $request->getQueryString();

        if ($queryString !== null) {
            $queryString = '?' . $queryString;
        }

        return $request->getBaseUrl() . $request->getPathInfo() . '/' . $queryString;
    }
}
