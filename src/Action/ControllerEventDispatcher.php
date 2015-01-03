<?php

namespace Emonkak\Framework\Action;

use Emonkak\Framework\Controller\ControllerEventListenerInterface;
use Emonkak\Framework\Routing\MatchedRoute;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class ControllerEventDispatcher implements ActionDispatcherInterface
{
    private $dispatcher;

    public function __construct(ActionDispatcherInterface $dispatcher)
    {
        $this->dispatcher = $dispatcher;
    }

    /**
     * {@inheritDoc}
     */
    public function dispatch(Request $request, MatchedRoute $match, $controller)
    {
        $isControllerEventListener = $controller instanceof ControllerEventListenerInterface;
        if ($isControllerEventListener) {
            $controller->onRequest($request);
        }

        $response = $this->dispatcher->dispatch($request, $match, $controller);

        if ($isControllerEventListener) {
            $controller->onResponse($request, $response);
        }

        return $response;
    }

    /**
     * {@inheritDoc}
     */
    public function canDispatch(Request $request, MatchedRoute $match, $controller)
    {
        return $this->dispatcher->canDispatch($request, $match, $controller);
    }
}
