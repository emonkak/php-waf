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
        $message = sprintf(
            '%s: "%s" at %s line %d',
            get_class($exception),
            $exception->getMessage(),
            $exception->getFile(),
            $exception->getLine()
        );
        $context = ['exception' => $exception];

        if ($exception->getStatusCode() >= 500) {
            $this->logger->critical($message, $context);
        } else {
            $this->logger->error($message, $context);
        }

        return $this->kernel->handleException($request, $exception);
    }
}
