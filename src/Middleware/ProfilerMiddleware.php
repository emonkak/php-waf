<?php

namespace Emonkak\Waf\Middleware;

use Emonkak\Waf\Exception\HttpException;
use Emonkak\Waf\KernelInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Profiler\Profiler;

class ProfilerMiddleware implements KernelInterface
{
    /**
     * @var KernelInterface
     */
    private $kernel;

    /**
     * @var Profiler
     */
    private $profiler;

    /**
     * @param KernelInterface $kernel
     * @param Profiler        $profiler
     */
    public function __construct(KernelInterface $kernel, Profiler $profiler)
    {
        $this->kernel = $kernel;
        $this->profiler = $profiler;
    }

    /**
     * {@inheritDoc}
     */
    public function handleRequest(Request $request)
    {
        $response = $this->kernel->handleRequest($request);
        $profile = $this->profiler->collect($request, $response);

        $this->profiler->saveProfile($profile);

        return $response;
    }

    /**
     * {@inheritDoc}
     */
    public function handleException(Request $request, HttpException $exception)
    {
        $response = $this->kernel->handleException($request, $exception);
        $profile = $this->profiler->collect($request, $response, $exception);

        $this->profiler->saveProfile($profile);

        return $response;
    }
}
