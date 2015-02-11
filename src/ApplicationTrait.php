<?php

namespace Emonkak\Waf;

use Emonkak\Waf\Exception\HttpException;
use Emonkak\Waf\Exception\HttpInternalServerErrorException;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;

/**
 * An application facade trait.
 */
trait ApplicationTrait
{
    /**
     * Handles the given request.
     *
     * @param RequestInterface $request
     * @return ResponseInterface
     */
    public function handle(RequestInterface $request)
    {
        $kernel = $this->getKernel();
        try {
            $response = $kernel->handleRequest($request);
        } catch (HttpException $e) {
            $response = $kernel->handleException($request, $e);
        } catch (\Exception $e) {
            $response = $kernel->handleException(
                $request,
                new HttpInternalServerErrorException('Uncaught exception', $e)
            );
        }

        return $response;
    }

    /**
     * Gets the kernel for this application.
     *
     * @return KernelInterface
     */
    abstract protected function getKernel();
}
