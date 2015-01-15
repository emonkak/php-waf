<?php

namespace Emonkak\Framework\Utils;

/**
 * Utilities for string.
 */
class StringUtils
{
    /**
     * @codeCoverageIgnore
     */
    private function __construct()
    {
    }

    /**
     * @param string $str
     * @param string $prefix
     * @return boolean
     */
    public static function forgetsTrailingSlash($path, $prefix)
    {
        return substr($prefix, -1) === '/' && $path === rtrim($prefix, '/');
    }

    /**
     * @param string $str
     * @param string $prefix
     * @return boolean
     */
    public static function startsWith($path, $prefix)
    {
        return strpos($path, $prefix) === 0;
    }

    /**
     * @param string $str
     * @return string
     */
    public static function toUpperCamelcase($str)
    {
        return preg_replace_callback('/(?:^|\_)([a-z])/', function (array $matches) {
            return strtoupper($matches[1]);
        }, $str);
    }

    /**
     * @param string $str
     * @return string
     */
    public static function toLowerCamelcase($str)
    {
        return preg_replace_callback('/\_([a-z])/', function (array $matches) {
            return strtoupper($matches[1]);
        }, $str);
    }
}
