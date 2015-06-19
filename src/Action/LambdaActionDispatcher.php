<?php

namespace Emonkak\Waf\Action;

use Emonkak\Waf\Exception\HttpBadRequestException;
use Emonkak\Waf\Exception\HttpNotFoundException;
use Emonkak\Waf\Routing\MatchedRoute;
use Emonkak\Waf\Utils\ReflectionUtils;
use Symfony\Component\HttpFoundation\Request;

/**
 * The action dispatcher for a callable controller.
 */
class LambdaActionDispatcher implements ActionDispatcherInterface
{
    /**
     * {@inheritDoc}
     */
    public function dispatch(Request $request, MatchedRoute $match, $controller)
    {
        $controllerReflection = new \ReflectionClass($match->controller);
        $actionName = '__invoke';

        try {
            $action = $controllerReflection->getMethod($actionName);
        } catch (\ReflectionException $e) {
            throw new HttpNotFoundException(sprintf(
                'Controller method "%s::%s()" can not be found.',
                $controllerReflection->getName(),
                $actionName
            ), $e);
        }

        // Add action as a parameter if action is not 'index'.
        $params = $match->action !== 'index'
            ? array_merge([$match->action], $match->params)
            : $match->params;

        if (!ReflectionUtils::matchesNumberOfArguments($action, count($params))) {
            throw new HttpBadRequestException(sprintf(
                'Number of arguments does not match to definition in the controller method "%s::%s()".',
                $controllerReflection->getName(),
                $actionName
            ));
        }

        return $action->invokeArgs($controller, $params);
    }

    /**
     * {@inheritDoc}
     */
    public function canDispatch(Request $request, MatchedRoute $match, $controller)
    {
        return is_callable($controller);
    }
}
