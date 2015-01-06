<?php

namespace Emonkak\Framework;

use Emonkak\Framework\Exception\HttpException;
use Emonkak\Framework\Exception\HttpInternalServerErrorException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * The application facade.
 */
class Application
{
    private $kernel;

    /**
     * @param KernelInterface $kernel
     */
    public function __construct(KernelInterface $kernel)
    {
        $this->kernel = $kernel;
    }

    /**
     * @param Request $request
     * @return Response
     */
    public function handle(Request $request)
    {
        try {
            return $this->kernel->handleRequest($request);
        } catch (HttpException $e) {
            return $this->kernel->handleException($request, $e);
        } catch (\Exception $e) {
            return $this->kernel->handleException(
                $request,
                new HttpInternalServerErrorException('Uncaught exception', $e)
            );
        }
    }
}
