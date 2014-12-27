<?php

namespace Emonkak\Framework\Middleware;

use Emonkak\Framework\KernelInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\HttpExceptionInterface;

class ExceptionHandlerMiddleware implements KernelInterface
{
    private $kernel;
    private $errorHandler;

    public function __construct(KernelInterface $kernel, callable $errorHandler)
    {
        $this->kernel = $kernel;
        $this->errorHandler = $errorHandler;
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
    public function handleException(Request $request, HttpExceptionInterface $exception)
    {
        try {
            return call_user_func($this->errorHandler, $request, $exception);
        } catch (HttpExceptionInterface $e) {
            return $this->kernel->handleException($request, $e);
        } catch (\Exception $e) {
            return $this->kernel->handleException($request, new InternalServerErrorException($e));
        }
    }
}
