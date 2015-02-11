<?php

namespace Emonkak\Framework\Instantiator;

use Emonkak\Di\ContainerInterface;
use Emonkak\Di\ContainerConfiguratorInterface;

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
        return $this->container->getInstance($className);
    }
}
