<?php

namespace Emonkak\Waf\Utils;

/**
 * Utilities for reflection.
 */
class ReflectionUtils
{
    /**
     * @codeCoverageIgnore
     */
    private function __construct()
    {
    }

    /**
     * @param \ReflectionFunctionAbstract $func
     * @param integer                     $num
     * @return boolean
     */
    public static function matchesNumberOfArguments(\ReflectionFunctionAbstract $func, $num)
    {
        return $func->getNumberOfRequiredParameters() <= $num
            && $func->getNumberOfParameters() >= $num;
    }
}
