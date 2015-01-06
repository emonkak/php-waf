<?php

namespace Emonkak\Framework;

use Emonkak\Framework\Exception\HttpException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * The kernel for an application.
 */
interface KernelInterface
{
    /**
     * Handles the given HTTP request.
     *
     * @param Request $request
     * @return Response
     * @throws \Exception
     */
    public function handleRequest(Request $request);

    /**
     * Handles the thrown exception.
     *
     * @param Request       $request
     * @param HttpException $exception
     * @return Response
     */
    public function handleException(Request $request, HttpException $exception);
}
