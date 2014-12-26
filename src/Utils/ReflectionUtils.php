<?php

namespace Emonkak\Framework\Utils;

class ReflectionUtils
{
    /**
     * @codeCoverageIgnore
     */
    private function __construct()
    {
    }

    /**
     * @param string $className
     * @return \ReflectionClass
     * @throws \ReflectionException
     */
    public static function getReflectionClass($className)
    {
        $class = new \ReflectionClass($className);
        if ($class->getName() !== $className) {
            throw new \ReflectionException(
                sprintf('Class "%s" does not match to "%s"', $className, $class->getName())
            );
        }
        return $class;
    }

    /**
     * @param \ReflectionClass $class
     * @param string           $methodName
     * @return \ReflectionMethod
     * @throws \ReflectionException
     */
    public static function getReflectionMethod(\ReflectionClass $class, $methodName)
    {
        $method = $class->getMethod($methodName);
        if ($method->getName() !== $methodName) {
            throw new \ReflectionException(sprintf('Method "%s::%s" does not match to "%s::%s"', 
                $class->getName(),
                $method->getName(),
                $class->getName(),
                $methodName
            ));
        }
        return $method;
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
