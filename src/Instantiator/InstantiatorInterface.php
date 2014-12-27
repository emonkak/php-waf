<?php

namespace Emonkak\Framework\Instantiator;

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
