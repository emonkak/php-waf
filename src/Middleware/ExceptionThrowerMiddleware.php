<?php

namespace Emonkak\Framework\Middleware;

use Emonkak\Framework\Exception\HttpException;
use Emonkak\Framework\KernelInterface;
use Symfony\Component\HttpFoundation\Request;

/**
 * The middleware to Throw an exception for debug.
 */
class ExceptionThrowerMiddleware implements KernelInterface
{
    /**
     * @var KernelInterface
     */
    private $kernel;

    /**
     * @var array (statusCode => true)
     */
    private $allowed = [];

    /**
     * @param KernelInterface $kernel
     */
    public function __construct(KernelInterface $kernel)
    {
        $this->kernel = $kernel;
    }

    /**
     * @param interger $statusCode
     * @return ExceptionThrowerMiddleware
     */
    public function allowStatusCode($statusCode)
    {
        $this->allowed[$statusCode] = true;
        return $this;
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
        $statusCode = $exception->getStatusCode();
        if (isset($this->allowed[$statusCode])) {
            return $this->kernel->handleException($request, $exception);
        }
        throw $exception;
    }
}
