<?php

namespace Emonkak\Framework;

use Emonkak\Framework\Exception\HttpException;
use Emonkak\Framework\Exception\HttpInternalServerErrorException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * An application facade trait.
 */
trait ApplicationTrait
{
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
