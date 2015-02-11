<?php

namespace Emonkak\Waf\Action;

use Emonkak\Waf\Exception\HttpNotFoundException;
use Emonkak\Waf\Utils\StringUtils;
use Psr\Http\Message\RequestInterface;

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
    protected function getActionName(RequestInterface $request, $name)
    {
        if ($name !== strtolower($name)) {
            throw new HttpNotFoundException('The action name must contain only lowercase letters');
        }

        return strtolower($request->getMethod()) . StringUtils::toUpperCamelcase($name);
    }
}
