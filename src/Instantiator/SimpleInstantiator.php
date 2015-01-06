<?php

namespace Emonkak\Framework\Instantiator;

class SimpleInstantiator implements InstantiatorInterface
{
    /**
     * {@inheritDoc}
     */
    public function instantiate(\ReflectionClass $class)
    {
        return $class->newInstance();
    }
}
