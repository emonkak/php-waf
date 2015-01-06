<?php

namespace Emonkak\Framework\Middleware;

use Emonkak\Framework\Exception\HttpException;
use Emonkak\Framework\KernelInterface;
use Psr\Log\LoggerInterface;
use Symfony\Component\HttpFoundation\Request;

/**
 * Provides the logging of an exception.
 */
class ExceptionLoggerMiddleware implements KernelInterface
{
    private $kernel;
    private $logger;

    /**
     * @param KernelInterface $kernel
     * @param LoggerInterface $logger
     */
    public function __construct(KernelInterface $kernel, LoggerInterface $logger)
    {
        $this->kernel = $kernel;
        $this->logger = $logger;
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
        $e = $exception;
        $n = 0;

        do {
            $this->logException($e, $n++);
        } while ($e = $e->getPrevious());

        return $this->kernel->handleException($request, $exception);
    }

    /**
     * @param \Exception $e
     * @param integer    $depth
     */
    private function logException(\Exception $e, $depth)
    {
        $message = sprintf(
            '%s "%s" with message "%s" at %s line %d',
            $depth === 0 ? 'Uncaught exception' : 'Caused by',
            get_class($e),
            $e->getMessage(),
            $e->getFile(),
            $e->getLine()
        );
        $context = ['exception' => $e];

        if (!($e instanceof HttpException) || $e->getStatusCode() >= 500) {
            $this->logger->critical($message, $context);
        } else {
            $this->logger->error($message, $context);
        }
    }
}
