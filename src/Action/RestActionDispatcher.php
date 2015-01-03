<?php

namespace Emonkak\Framework\Action;

use Emonkak\Framework\Utils\StringUtils;
use Symfony\Component\HttpFoundation\Request;

class RestActionDispatcher extends AbstractActionDispatcher
{
    /**
     * {@inheritDoc}
     */
    protected function getActionName(Request $request, $name)
    {
        return strtolower($request->getMethod()) . StringUtils::toUpperCamelcase($name);
    }
}
