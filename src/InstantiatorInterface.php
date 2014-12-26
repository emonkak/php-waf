<?php

namespace Emonkak\Framework;

interface InstantiatorInterface
{
    /**
     * Instantiates the given class.
     *
     * @param \ReflectionClass $className
     * @return mixed
     */
    public function instantiate(\ReflectionClass $class);
}
