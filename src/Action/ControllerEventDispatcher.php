<?php

namespace Emonkak\Waf\Action;

use Emonkak\Waf\Controller\ControllerEventListenerInterface;
use Emonkak\Waf\Routing\MatchedRoute;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

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
    public function dispatch(Request $request, MatchedRoute $match, $controller)
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
    public function canDispatch(Request $request, MatchedRoute $match, $controller)
    {
        return $this->dispatcher->canDispatch($request, $match, $controller);
    }
}
