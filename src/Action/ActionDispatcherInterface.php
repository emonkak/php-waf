<?php

namespace Emonkak\Framework\Action;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

interface ActionDispatcherInterface
{
    /**
     * @param Request         $request    The request to dispatch
     * @param ActionInterface $action     The action reference
     * @param mixed           $controller The controller instance
     * @return Response
     */
    public function dispatch(Request $request, ActionInterface $action, $controller);
}
