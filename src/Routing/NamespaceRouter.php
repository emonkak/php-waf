<?php

namespace Emonkak\Framework\Routing;

use Emonkak\Framework\Action\ControllerAction;
use Emonkak\Framework\Exception\HttpNotFoundException;
use Emonkak\Framework\Utils\ReflectionUtils;
use Emonkak\Framework\Utils\StringUtils;
use Symfony\Component\HttpFoundation\Request;

class NamespaceRouter implements RouterInterface
{
    private $prefix;
    private $namespace;

    public function __construct($prefix, $namespace)
    {
        $this->prefix = $prefix;
        $this->namespace = $namespace;
    }

    /**
     * {@inheritDoc}
     */
    public function match(Request $request)
    {
        $path = $request->getPathInfo();

        if (strpos($path, $this->prefix) === 0) {
            $rest = explode('/', substr($path, strlen($this->prefix)));
            if (!isset($rest[0])) $rest[0] = 'index';
            if (!isset($rest[1])) $rest[1] = 'index';

            $className = $this->getClassName($request, $rest[0]);
            try {
                $controller = ReflectionUtils::getReflectionClass($className);
            } catch (\ReflectionException $e) {
                throw new HttpNotFoundException(
                    sprintf('Controller class "%s" can not be found.', $className),
                    $e
                );
            }

            $methodName = $this->getMethodName($request, $rest[1]);
            try {
                $action = ReflectionUtils::getReflectionMethod($controller, $methodName);
                $args = array_slice($rest, 2);
            } catch (\ReflectionException $e) {
                // Fallback to "show" action.
                try {
                    $showMethodName = $this->getMethodName($request, 'show');
                    $action = ReflectionUtils::getReflectionMethod($controller, $showMethodName);
                    $args = array_slice($rest, 1);
                } catch (\ReflectionException $_) {
                    throw new HttpNotFoundException(
                        sprintf('Controller method "%s::%s()" can not be found.', $className, $methodName),
                        $e
                    );
                }
            }

            return new ControllerAction($controller, $action, $args);
        }

        return null;
    }

    protected function getClassName(Request $request, $name)
    {
        return $this->namespace . '\\' . StringUtils::camelize($name) . 'Controller';
    }

    protected function getMethodName(Request $request, $name)
    {
        return strtolower($request->getMethod()) . StringUtils::camelize($name);
    }
}
