<?php

namespace Emonkak\Waf\Utils;

use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\UriInterface;

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
     * @param UriInterface $uri
     * @return string
     */
    public static function completeTrailingSlash(UriInterface $uri)
    {
        $queryString = $uri->getQuery();

        if ($queryString !== '') {
            $queryString = '?' . $queryString;
        }

        return $uri->getPath() . '/' . $queryString;
    }
}
