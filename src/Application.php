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
     * Handles the given request.
     *
     * @param Request $request
     * @return Response
     */
    public function handle(Request $request)
    {
        $this->onRequest($request);

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

        $this->onResponse($request, $response);

        return $response;
    }

    /**
     * Gets the kernel for this application.
     *
     * @return KernelInterface
     */
    abstract protected function getKernel();

    /**
     * This method will be called before handle request.
     *
     * @param Request $request
     */
    abstract protected function onRequest(Request $request);

    /**
     * This method will be called after handle request.
     *
     * @param Request $request
     * @param Response $response
     */
    abstract protected function onResponse(Request $request, Response $response);
}
