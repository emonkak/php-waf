<?php

namespace Emonkak\Waf\Instantiator;

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
