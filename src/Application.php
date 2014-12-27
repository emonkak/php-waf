<?php

namespace Emonkak\Framework;

use Emonkak\Framework\Exception\InternalServerErrorException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\HttpExceptionInterface;

class Application
{
    private $kernel;

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
        } catch (HttpExceptionInterface $e) {
            return $this->kernel->handleException($request, $e);
        } catch (\Exception $e) {
            return $this->kernel->handleException(
                $request,
                new InternalServerErrorException('Uncaught exception.', $e)
            );
        }
    }
}
