<?php

namespace Emonkak\Framework\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

interface ControllerEventListenerInterface
{
    /**
     * This method will be called before the controller action is invoked.
     *
     * @param Request $request
     */
    public function onRequest(Request $request);

    /**
     * This method will be called after the controller action is invoked.
     *
     * @param Request $request
     * @param Response $response
     */
    public function onResponse(Request $request, Response $response);
}
