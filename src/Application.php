<?php

namespace Emonkak\Framework;

use Emonkak\Framework\Exception\HttpException;
use Emonkak\Framework\Exception\HttpInternalServerErrorException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * The application facade.
 */
trait Application
{
    /**
     * Gets the kernel for this application.
     *
     * @return KernelInterface
     */
    abstract public function getKernel();

    /**
     * Handles the given request.
     *
     * @param Request $request
     * @return Response
     */
    public function handle(Request $request)
    {
        $kernel = $this->getKernel();
        try {
            return $kernel->handleRequest($request);
        } catch (HttpException $e) {
            return $kernel->handleException($request, $e);
        } catch (\Exception $e) {
            return $kernel->handleException(
                $request,
                new HttpInternalServerErrorException('Uncaught exception', $e)
            );
        }
    }
}
