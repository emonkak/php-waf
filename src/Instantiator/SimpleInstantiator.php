<?php

namespace Emonkak\Framework\Instantiator;

use Emonkak\Framework\Utils\ReflectionUtils;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

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
