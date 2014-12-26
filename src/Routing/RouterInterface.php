<?php

namespace Emonkak\Framework\Routing;

use Emonkak\Framework\Action\ActionInterface;
use Symfony\Component\HttpFoundation\Request;

interface RouterInterface
{
    /**
     * @param Request $request
     * @return ActionInterface
     */
    public function match(Request $request);
}
