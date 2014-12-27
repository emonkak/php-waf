<?php

namespace Emonkak\Framework\Utils;

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
     * @return string
     */
    public static function camelize($str)
    {
        return preg_replace_callback('/(?:^|\_)([a-z])/', function (array $matches) {
            return strtoupper($matches[1]);
        }, $str);
    }
}