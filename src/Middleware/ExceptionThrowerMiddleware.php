<?php

namespace Emonkak\Waf\Middleware;

use Emonkak\Waf\Exception\HttpException;
use Emonkak\Waf\KernelInterface;
use Psr\Http\Message\RequestInterface;

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
     * @var boolean
     */
    private $allowRedirection = false;

    /**
     * @var boolean
     */
    private $allowClientError = false;

    /**
     * @var boolean
     */
    private $allowServerError = false;

    /**
     * @param KernelInterface $kernel
     */
    public function __construct(KernelInterface $kernel)
    {
        $this->kernel = $kernel;
    }

    /**
     * @param boolean $enabled
     * @return ExceptionThrowerMiddleware
     */
    public function allowRedirection($enabled = true)
    {
        $this->allowRedirection = $enabled;
        return $this;
    }

    /**
     * @param boolean $enabled
     * @return ExceptionThrowerMiddleware
     */
    public function allowClientError($enabled = true)
    {
        $this->allowClientError = $enabled;
        return $this;
    }

    /**
     * @param boolean $enabled
     * @return ExceptionThrowerMiddleware
     */
    public function allowServerError($enabled = true)
    {
        $this->allowServerError = $enabled;
        return $this;
    }

    /**
     * {@inheritDoc}
     */
    public function handleRequest(RequestInterface $request)
    {
        return $this->kernel->handleRequest($request);
    }

    /**
     * {@inheritDoc}
     */
    public function handleException(RequestInterface $request, HttpException $exception)
    {
        if ($this->isAllowed($exception->getStatusCode())) {
            return $this->kernel->handleException($request, $exception);
        }

        throw $exception;
    }

    /**
     * @param interger $statusCode
     * @return boolean
     */
    protected function isAllowed($statusCode)
    {
        if ($statusCode >= 500) {
            return $this->allowServerError;
        } elseif ($statusCode >= 400) {
            return $this->allowClientError;
        } elseif ($statusCode >= 300) {
            return $this->allowRedirection;
        } else {
            return false;
        }
    }
}
