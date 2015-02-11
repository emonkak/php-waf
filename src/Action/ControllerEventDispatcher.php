<?php

namespace Emonkak\Waf\Action;

use Emonkak\Waf\Controller\ControllerEventListenerInterface;
use Emonkak\Waf\Routing\MatchedRoute;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;

class ControllerEventDispatcher implements ActionDispatcherInterface
{
    /**
     * @var ActionDispatcherInterface
     */
    private $dispatcher;

    /**
     * @param ActionDispatcherInterface $dispatcher
     */
    public function __construct(ActionDispatcherInterface $dispatcher)
    {
        $this->dispatcher = $dispatcher;
    }

    /**
     * {@inheritDoc}
     */
    public function dispatch(RequestInterface $request, MatchedRoute $match, $controller)
    {
        $isControllerEventListener = $controller instanceof ControllerEventListenerInterface;
        if ($isControllerEventListener) {
            $response = $controller->onRequest($request);
            if ($response) {
                return $response;
            }
        }

        $response = $this->dispatcher->dispatch($request, $match, $controller);

        if ($isControllerEventListener) {
            $response = $controller->onResponse($request, $response) ?: $response;
        }

        return $response;
    }

    /**
     * {@inheritDoc}
     */
    public function canDispatch(RequestInterface $request, MatchedRoute $match, $controller)
    {
        return $this->dispatcher->canDispatch($request, $match, $controller);
    }
}
