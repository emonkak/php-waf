<?php

namespace Emonkak\Framework\Action;

use Emonkak\Framework\Utils\StringUtils;
use Symfony\Component\HttpFoundation\Request;

class StandardActionDispatcher extends AbstractActionDispatcher
{
    /**
     * {@inheritDoc}
     */
    protected function getActionName(Request $request, $name)
    {
        return StringUtils::toLowerCamelcase($name) . 'Action';
    }
}
