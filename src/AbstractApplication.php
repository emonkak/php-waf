<?php

namespace Emonkak\Framework;

use Emonkak\Di\Container;
use Emonkak\Di\ContainerInterface;
use Emonkak\Framework\Exception\HttpException;
use Emonkak\Framework\Exception\HttpInternalServerErrorException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

abstract class AbstractApplication
{
    use Application {
        handle as protected doHandle;
    }

    /**
     * @var array
     */
    protected $configs;

    /**
     * @var ContainerInterface
     */
    protected $container;

    /**
     * @var boolean
     */
    private $booted = false;

    /**
     * @param array $config
     */
    public function __construct(array $config = [])
    {
        $this->config = $config;
        $this->container = $this->prepareContainer();
    }

    /**
     * Boots the application.
     */
    final public function boot()
    {
        $this->doBoot();

        $this->booted = true;
    }

    /**
     * {@inheritdoc}
     */
    final public function handle(Request $request)
    {
        if (!$this->booted) {
            $this->boot();
        }

        if ($this->container->has('Symfony\Component\HttpFoundation\Session\SessionInterface')) {
            $request->setSession($this->container->getInstance('Symfony\Component\HttpFoundation\Session\SessionInterface'));
        }

        $this->container->set('Symfony\Component\HttpFoundation\Request', $request);

        return $this->doHandle($request);
    }

    /**
     * Write the application initialization here.
     *
     * @codeCoverageIgnore
     */
    protected function doBoot()
    {
    }

    /**
     * {@inheritdoc}
     */
    protected function getKernel()
    {
        return $this->container->getInstance('Emonkak\Framework\KernelInterface');
    }

    /**
     * @return ContainerInterface
     */
    abstract protected function prepareContainer();
}
