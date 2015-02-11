<?php

namespace Emonkak\Waf\Action;

use Emonkak\Waf\Exception\HttpException;
use Emonkak\Waf\Routing\MatchedRoute;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;

interface ActionDispatcherInterface
{
    /**
     * @param RequestInterface $request    The request to dispatch
     * @param MatchedRoute     $match      The matched routing result
     * @param mixed            $controller The controller instance
     * @return ResponseInterface
     * @throws HttpException
     */
    public function dispatch(RequestInterface $request, MatchedRoute $match, $controller);

    /**
     * @param RequestInterface $request    The request to dispatch
     * @param MatchedRoute     $match      The matched routing result
     * @param mixed            $controller The controller instance
     * @return boolean
     */
    public function canDispatch(RequestInterface $request, MatchedRoute $match, $controller);
}
