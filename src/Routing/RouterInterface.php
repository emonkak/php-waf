<?php

namespace Emonkak\Framework\Routing;

use Emonkak\Framework\Action\ActionInterface;
use Emonkak\Framework\Exception\HttpException;
use Symfony\Component\HttpFoundation\Request;

interface RouterInterface
{
    /**
     * @param Request $request
     * @return MatchesRoute
     * @throws HttpException
     */
    public function match(Request $request);
}
