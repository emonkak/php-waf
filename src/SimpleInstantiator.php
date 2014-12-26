<?php

namespace Emonkak\Framework;

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
