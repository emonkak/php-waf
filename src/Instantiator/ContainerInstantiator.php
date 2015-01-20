<?php

namespace Emonkak\Framework\Instantiator;

use Emonkak\Di\Container;
use Emonkak\Di\ContainerConfiguratorInterface;
use Doctrine\Common\Cache\Cache;

class ContainerInstantiator implements InstantiatorInterface
{
    /**
     * @var Container
     */
    private $container;

    /**
     * @param Container $container
     */
    public function __construct(Container $container)
    {
        $this->container = $container;
    }

    /**
     * {@inheritDoc}
     */
    public function instantiate($className)
    {
        $value = $this->container->get($className);

        return $value->inject();
    }
}
