<?php

namespace Emonkak\Waf\Action;

use Emonkak\Waf\Exception\HttpException;
use Emonkak\Waf\Routing\MatchedRoute;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

interface ActionDispatcherInterface
{
    /**
     * @param Request      $request    The request to dispatch
     * @param MatchedRoute $match      The matched routing result
     * @param mixed        $controller The controller instance
     * @return Response
     * @throws HttpException
     */
    public function dispatch(Request $request, MatchedRoute $match, $controller);

    /**
     * @param Request      $request    The request to dispatch
     * @param MatchedRoute $match      The matched routing result
     * @param mixed        $controller The controller instance
     * @return boolean
     */
    public function canDispatch(Request $request, MatchedRoute $match, $controller);
}
