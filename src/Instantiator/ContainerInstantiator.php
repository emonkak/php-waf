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
     * @var Cache
     */
    private $cache;

    /**
     * @var callable[]
     */
    private $configurators = [];

    /**
     * @param Container $container
     * @param Cache     $cache
     */
    public function __construct(Container $container, Cache $cache)
    {
        $this->container = $container;
        $this->cache = $cache;
    }

    /**
     * {@inheritDoc}
     */
    public function instantiate($className)
    {
        if ($this->cache->contains($className)) {
            $value = $this->cache->fetch($className);
        } else {
            $this->configure($this->container);
            $value = $this->container->get($className);
            $this->cache->save($className, $value);
        }

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
