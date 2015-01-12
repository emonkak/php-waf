<?php

namespace Emonkak\Framework\Instantiator;

use Emonkak\Di\Container;
use Doctrine\Common\Cache\Cache;

class ContainerInstantiator implements InstantiatorInterface
{
    /**
     * @var Container
     */
    private $container;

    /**
     * @var callable[]
     */
    private $configurators = [];

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
        $this->configure($this->container);

        $value = $this->container->get($className);

        return $value->inject();
    }

    /**
     * Adds the container configurator.
     *
     * @param callable $configurator
     */
    public function addConfigurator(callable $configurator)
    {
        $this->configurators[] = $configurator;
    }

    /**
     * Configures the container.
     *
     * @param Container $container
     */
    protected function configure(Container $container)
    {
        foreach ($this->configurators as $configurator) {
            call_user_func($configurator, $container);
        }
        $this->configurators = [];
    }
}
