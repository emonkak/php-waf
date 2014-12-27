<?php

namespace Emonkak\Framework\Middleware;

use Emonkak\Framework\KernelInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\HttpExceptionInterface;
use Symfony\Component\HttpKernel\Profiler\Profiler;

class ProfilerMiddleware implements KernelInterface
{
    private $kernel;
    private $profiler;

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
    public function handleException(Request $request, HttpExceptionInterface $exception)
    {
        $response = $this->kernel->handleException($request, $exception);
        $profile = $this->profiler->collect($request, $response, $exception);
        $this->profiler->saveProfile($profile);
        return $response;
    }
}
