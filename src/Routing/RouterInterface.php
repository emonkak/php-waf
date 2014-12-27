<?php

namespace Emonkak\Framework\Routing;

use Emonkak\Framework\Action\ActionInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\HttpExceptionInterface;

interface RouterInterface
{
    /**
     * @param Request $request
     * @return MatchesRoute
     * @throws HttpExceptionInterface
     */
    public function match(Request $request);
}
