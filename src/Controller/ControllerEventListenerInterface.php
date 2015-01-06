<?php

namespace Emonkak\Framework\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Represents the controller event listener.
 */
interface ControllerEventListenerInterface
{
    /**
     * This method will be called before the controller action is invoked.
     *
     * @param Request $request
     * @return Response|null
     */
    public function onRequest(Request $request);

    /**
     * This method will be called after the controller action is invoked.
     *
     * @param Request $request
     * @param Response $response
     * @return Response|null
     */
    public function onResponse(Request $request, Response $response);
}
