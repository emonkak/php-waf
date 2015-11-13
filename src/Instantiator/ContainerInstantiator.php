<?php

namespace Emonkak\Waf\Instantiator;

use Interop\Container\ContainerInterface;

class ContainerInstantiator implements InstantiatorInterface
{
    /**
     * @var ContainerInterface
     */
    private $container;

    /**
     * @param ContainerInterface $container
     */
    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
    }

    /**
     * {@inheritDoc}
     */
    public function instantiate($className)
    {
        return $this->container->get($className);
    }
}
