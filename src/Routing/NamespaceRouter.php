<?php

namespace Emonkak\Framework\Routing;

use Emonkak\Framework\Exception\HttpNotFoundException;
use Emonkak\Framework\Exception\HttpRedirectException;
use Emonkak\Framework\Utils\ReflectionUtils;
use Emonkak\Framework\Utils\StringUtils;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

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
            $fragments = explode('/', substr($path, strlen($this->prefix)));

            if (count($fragments) <= 1 && substr($path, -1) !== '/') {
                // Complete the slash
                throw new HttpRedirectException($path . '/', Response::HTTP_MOVED_PERMANENTLY);
            }

            if (empty($fragments[0])) $fragments[0] = 'index';
            if (empty($fragments[1])) $fragments[1] = 'index';

            $controller = $this->getController($fragments[0]);
            try {
                $controllerReflection = ReflectionUtils::getClass($controller);
            } catch (\ReflectionException $e) {
                throw new HttpNotFoundException(
                    sprintf('Controller "%s" can not be found.', $controller),
                    $e
                );
            }
            $action = $fragments[1];
            $params = array_slice($fragments, 2);

            return new MatchedRoute($controllerReflection, $action, $params);
        }

        return null;
    }

    protected function getController($name)
    {
        return $this->namespace . '\\' . StringUtils::toUpperCamelcase($name) . 'Controller';
    }
}
