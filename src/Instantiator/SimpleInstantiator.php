<?php

namespace Emonkak\Framework\Instantiator;

use Emonkak\Framework\Utils\ReflectionUtils;

class SimpleInstantiator implements InstantiatorInterface
{
    /**
     * {@inheritDoc}
     */
    public function instantiate($className)
    {
        $class = ReflectionUtils::getClass($className);
        return $class->newInstance();
    }
}
