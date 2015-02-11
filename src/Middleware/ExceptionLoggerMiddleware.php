<?php

namespace Emonkak\Waf\Middleware;

use Emonkak\Waf\Exception\HttpException;
use Emonkak\Waf\KernelInterface;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Log\LogLevel;
use Psr\Log\LoggerInterface;

/**
 * Provides the logging of an exception.
 */
class ExceptionLoggerMiddleware implements KernelInterface
{
    /**
     * @var KernelInterface
     */
    private $kernel;

    /**
     * @var LoggerInterface
     */
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
    public function handleRequest(RequestInterface $request)
    {
        return $this->kernel->handleRequest($request);
    }

    /**
     * {@inheritDoc}
     */
    public function handleException(RequestInterface $request, HttpException $exception)
    {
        $this->logger->log(
            $this->getLogLevel($exception),
            $this->getMessage($exception),
            ['exception' => $exception]
        );

        return $this->kernel->handleException($request, $exception);
    }

    /**
     * @param HttpException $e
     * @return string
     */
    protected function getLogLevel(HttpException $e)
    {
        $statusCode = $e->getStatusCode();
        if ($statusCode >= 500) {
            return LogLevel::ERROR;
        } elseif ($statusCode >= 400) {
            return LogLevel::WARNING;
        } else {
            return LogLevel::INFO;
        }
    }

    /**
     * @param HttpException $e
     * @return string
     */
    protected function getMessage(HttpException $e)
    {
        $fragments = [];

        do {
            $fragments[] = sprintf(
                '"%s" with message "%s" at %s line %d',
                get_class($e),
                $e->getMessage(),
                $e->getFile(),
                $e->getLine()
            );
        } while ($e = $e->getPrevious());

        return 'Uncaught exception ' . implode(' - Caused by ', $fragments);
    }
}
