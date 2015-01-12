<?php

namespace Emonkak\Framework\Instantiator;

use Emonkak\Di\Container;
use Doctrine\Common\Cache\Cache;

class CachedInstantiator implements InstantiatorInterface
{
    /**
     * @var InstantiatorInterface
     */
    private $instantiator;

    /**
     * @var Cache
     */
    private $cache;

    /**
     * @param InstantiatorInterface $instantiator
     * @param Cache                 $cache
     */
    public function __construct(InstantiatorInterface $instantiator, Cache $cache)
    {
        $this->instantiator = $instantiator;
        $this->cache = $cache;
    }

    /**
     * {@inheritDoc}
     */
    public function instantiate($className)
    {
        if ($this->cache->contains($className)) {
            return $this->cache->fetch($className);
        } else {
            $instance = $this->instantiator->instantiate($className);
            $this->cache->save($className, $instance);
            return $instance;
        }
    }
}
