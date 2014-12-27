<?php

namespace Emonkak\Framework;

use Emonkak\Framework\Exception\HttpException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

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
     * @param HttpException $exception
     * @return Response
     */
    public function handleException(Request $request, HttpException $exception);
}
