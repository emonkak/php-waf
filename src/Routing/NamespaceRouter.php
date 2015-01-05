<?php

namespace Emonkak\Framework\Routing;

use Emonkak\Framework\Exception\HttpNotFoundException;
use Emonkak\Framework\Exception\HttpRedirectException;
use Emonkak\Framework\Utils\ReflectionUtils;
use Emonkak\Framework\Utils\StringUtils;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Provides a routing from a namespace for controller classes.
 *
 * This follows a pattern such as "/{controller}/{action}/{params}...".
 */
class NamespaceRouter implements RouterInterface
{
    private $prefix;
    private $namespace;

    /**
     * @param string $prefix    The prefix of a request path.
     * @param string $namespace The namespace for controller classes.
     */
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

            $controllerName = empty($fragments[0]) ? 'index' : $fragments[0];
            $controller = $this->getController($controllerName);
            try {
                $controllerReflection = ReflectionUtils::getClass($controller);
            } catch (\ReflectionException $e) {
                throw new HttpNotFoundException(
                    sprintf('Controller "%s" can not be found.', $controller),
                    $e
                );
            }

            if (count($fragments) <= 1 && substr($path, -1) !== '/') {
                // Complete the slash
                throw new HttpRedirectException($path . '/', Response::HTTP_MOVED_PERMANENTLY);
            }

            $action = empty($fragments[1]) ? 'index' : $fragments[1];
            $params = array_slice($fragments, 2);

            return new MatchedRoute($controllerReflection, $action, $params);
        }

        return null;
    }

    /**
     * Returns the fully qualified controller class.
     *
     * @param string $name The fragment of controller name.
     * @return string
     */
    protected function getController($name)
    {
        return $this->namespace . '\\' . StringUtils::toUpperCamelcase($name) . 'Controller';
    }
}
