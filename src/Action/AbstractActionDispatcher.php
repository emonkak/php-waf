<?php

namespace Emonkak\Framework\Action;

use Emonkak\Framework\Exception\HttpBadRequestException;
use Emonkak\Framework\Exception\HttpNotFoundException;
use Emonkak\Framework\Routing\MatchedRoute;
use Emonkak\Framework\Utils\ReflectionUtils;
use Emonkak\Framework\Utils\StringUtils;
use Symfony\Component\HttpFoundation\Request;

abstract class AbstractActionDispatcher implements ActionDispatcherInterface
{
    /**
     * {@inheritDoc}
     */
    public function dispatch(Request $request, MatchedRoute $match, $controller)
    {
        $controllerReflection = new \ReflectionClass($match->controller);
        $actionName = $this->getActionName($request, $match->action);

        try {
            $action = $controllerReflection->getMethod($actionName);
        } catch (\ReflectionException $e) {
            throw new HttpNotFoundException(sprintf(
                'Controller method "%s::%s()" can not be found.',
                $controllerReflection->getName(),
                $actionName
            ), $e);
        }

        if (!ReflectionUtils::matchesNumberOfArguments($action, count($match->params))) {
            throw new HttpBadRequestException(sprintf(
                'Number of arguments does not match to definition in the controller method "%s::%s()".',
                $controllerReflection->getName(),
                $actionName
            ));
        }

        return $action->invokeArgs($controller, $match->params);
    }

    /**
     * {@inheritDoc}
     */
    public function canDispatch(Request $request, MatchedRoute $match, $controller)
    {
        return true;
    }

    /**
     * @param Request $request
     * @param string $name
     * @return string
     * @throws HttpException
     */
    abstract protected function getActionName(Request $request, $name);
}
