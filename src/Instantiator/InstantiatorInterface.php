<?php

namespace Emonkak\Framework\Instantiator;

/**
 * Provides instantiating of any class.
 */
interface InstantiatorInterface
{
    /**
     * Instantiates the given class.
     *
     * @param \ReflectionClass $class
     * @return mixed
     */
    public function instantiate(\ReflectionClass $class);
}
