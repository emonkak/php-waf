<?php

namespace Emonkak\Framework\Middleware;

use Emonkak\Framework\Exception\HttpException;
use Emonkak\Framework\KernelInterface;
use Psr\Log\LogLevel;
use Psr\Log\LoggerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

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
     * @var array
     */
    private $logLevels = [];

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
     * Sets the mapping between a status code and a log level.
     *
     * @param array $logLevels e.g. array(404 => LogLevel::INFO)
     */
    public function setLogLevels(array $logLevels)
    {
        $this->logLevels = $logLevels;
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
        $logLevel = $this->getLogLevel($e);
        $message = sprintf(
            '%s "%s" with message "%s" at %s line %d',
            $depth === 0 ? 'Uncaught exception' : 'Caused by',
            get_class($e),
            $e->getMessage(),
            $e->getFile(),
            $e->getLine()
        );
        $context = ['exception' => $e];

        $this->logger->log($logLevel, $message, $context);
    }

    /**
     * @param \Exception $e
     * @return string
     */
    private function getLogLevel(\Exception $e)
    {
        if ($e instanceof HttpException) {
            $statusCode = $e->getStatusCode();
            if (isset($this->logLevels[$statusCode])) {
                return $this->logLevels[$statusCode];
            } else {
                return $statusCode >= 500 ? LogLevel::EMERGENCY : LogLevel::ERROR;
            }
        } else {
            return LogLevel::EMERGENCY;
        }
    }
}
