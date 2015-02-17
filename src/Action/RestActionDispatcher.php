<?php

namespace Emonkak\Waf\Action;

use Emonkak\Waf\Exception\HttpNotFoundException;
use Emonkak\Waf\Utils\StringUtils;
use Symfony\Component\HttpFoundation\Request;

/**
 * Restful action dispatcher.
 *
 * e.g. 'getIndex()'
 */
class RestActionDispatcher extends AbstractActionDispatcher
{
    /**
     * {@inheritDoc}
     */
    protected function getActionName(Request $request, $name)
    {
        if ($name !== strtolower($name)) {
            throw new HttpNotFoundException('The action name must contain only lowercase letters');
        }

        return strtolower($request->getMethod()) . StringUtils::toUpperCamelcase($name);
    }
}
