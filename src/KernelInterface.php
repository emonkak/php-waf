<?php

namespace Emonkak\Waf;

use Emonkak\Waf\Exception\HttpException;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;

/**
 * The kernel for an application.
 */
interface KernelInterface
{
    /**
     * Handles the given HTTP request.
     *
     * @param RequestInterface $request
     * @return ResponseInterface
     * @throws \Exception
     */
    public function handleRequest(RequestInterface $request);

    /**
     * Handles the thrown exception.
     *
     * @param RequestInterface $request
     * @param HttpException    $exception
     * @return ResponseInterface
     */
    public function handleException(RequestInterface $request, HttpException $exception);
}
