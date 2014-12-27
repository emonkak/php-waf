<?php

namespace Emonkak\Framework;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\HttpExceptionInterface;

interface KernelInterface
{
    /**
     * @param Request $request
     * @return Response
     * @throws \Exception
     */
    public function handleRequest(Request $request);

    /**
     * @param Request $request
     * @param HttpExceptionInterface $exceptin
     * @return Response
     */
    public function handleException(Request $request, HttpExceptionInterface $exception);
}
