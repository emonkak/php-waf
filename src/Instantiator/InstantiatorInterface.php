<?php

namespace Emonkak\Waf\Instantiator;

/**
 * Provides instantiating of any class.
 */
interface InstantiatorInterface
{
    /**
     * Instantiates from the given class name.
     *
     * @param string $className
     * @return mixed
     */
    public function instantiate($className);
}
