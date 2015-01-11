<?php

namespace Emonkak\Framework\Instantiator;

class SimpleInstantiator implements InstantiatorInterface
{
    /**
     * {@inheritDoc}
     */
    public function instantiate($className)
    {
        return new $className();
    }
}
