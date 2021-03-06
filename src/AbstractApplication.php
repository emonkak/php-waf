<?php

namespace Emonkak\Waf;

use Emonkak\Di\AbstractContainer;
use Emonkak\Waf\Exception\HttpException;
use Emonkak\Waf\Exception\HttpInternalServerErrorException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

abstract class AbstractApplication
{
    use ApplicationTrait {
        handle as protected doHandle;
    }

    /**
     * @var array
     */
    protected $configs;

    /**
     * @var AbstractContainer
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
            $request->setSession($this->container->get('Symfony\Component\HttpFoundation\Session\SessionInterface'));
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
        return $this->container->get('Emonkak\Waf\KernelInterface');
    }

    /**
     * @return AbstractContainer
     */
    abstract protected function prepareContainer();
}
