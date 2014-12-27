<?php

namespace Emonkak\Framework\Instantiator;

interface InstantiatorInterface
{
    /**
     * Instantiates the given class.
     *
     * @param string $className
     * @return mixed
     */
    public function instantiate(\ReflectionClass $class);
}
