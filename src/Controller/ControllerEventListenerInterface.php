<?php

namespace Emonkak\Waf\Controller;

use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;

/**
 * Represents the controller event listener.
 */
interface ControllerEventListenerInterface
{
    /**
     * This method will be called before the controller action is invoked.
     *
     * @param RequestInterface $request
     * @return ResponseInterface|null
     */
    public function onRequest(RequestInterface $request);

    /**
     * This method will be called after the controller action is invoked.
     *
     * @param RequestInterface $request
     * @param ResponseInterface $response
     * @return ResponseInterface|null
     */
    public function onResponse(RequestInterface $request, ResponseInterface $response);
}
