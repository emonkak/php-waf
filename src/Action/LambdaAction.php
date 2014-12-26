<?php

namespace Emonkak\Framework\Action;

use Emonkak\Framework\InstantiatorInterface;
use Emonkak\Framework\Utils\ReflectionUtils;
use Symfony\Component\HttpFoundation\Request;

class LambdaAction implements ActionInterface
{
    private $lambda;
    private $args;

    public function __construct(\Closure $lambda, array $args)
    {
        $this->lambda = $lambda;
        $this->args = $args;
    }

    /**
     * {@inheritDoc}
     */
    public function call($controller)
    {
        return call_user_func_array($controller, $this->args);
    }

    /**
     * {@inheritDoc}
     */
    public function canCall()
    {
        $object = new \ReflectionObject($this->lambda);
        $method = $object->getMethod('__invoke');
        return ReflectionUtils::matchesNumberOfArguments($method, count($this->args));
    }

    /**
     * {@inheritDoc}
     */
    public function instantiateBy(InstantiatorInterface $instantiator)
    {
        return $this->lambda;
    }

    /**
     * {@inheritDoc}
     */
    public function __toString()
    {
        return 'Closure::__invoke()';
    }
}
