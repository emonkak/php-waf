<?php

namespace Emonkak\Framework\Middleware;

use Emonkak\Framework\Exception\HttpException;
use Emonkak\Framework\KernelInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Provides the handing of redirect.
 */
class RedirectHandlerMiddleware implements KernelInterface
{
    private $kernel;

    /**
     * @param KernelInterface $kernel
     */
    public function __construct(KernelInterface $kernel)
    {
        $this->kernel = $kernel;
    }

    /**
     * {@inheritDoc}
     */
    public function handleRequest(Request $request)
    {
        return $this->kernel->handleRequest($request);
    }

    /**
     * {@inheritDoc}
     */
    public function handleException(Request $request, HttpException $exception)
    {
        switch ($exception->getStatusCode()) {
        case Response::HTTP_MOVED_PERMANENTLY:
        case Response::HTTP_FOUND:
        case Response::HTTP_SEE_OTHER:
        case Response::HTTP_TEMPORARY_REDIRECT:
            return new Response('', $exception->getStatusCode(), $exception->getHeaders());
        default:
            return $this->kernel->handleException($request, $exception);
        }
    }
}
