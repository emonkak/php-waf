<?php

namespace Emonkak\Framework\Routing;

use Emonkak\Framework\Exception\HttpNotFoundException;
use Emonkak\Framework\Exception\HttpRedirectException;
use Emonkak\Framework\Utils\ReflectionUtils;
use Emonkak\Framework\Utils\StringUtils;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Provides a Restful resource routing.
 *
 * This follows a pattern such as '/{controller}/{resource}/{action}/{params}...'.
 */
class ResourceRouter implements RouterInterface
{
    private $prefix;
    private $controller;

    /**
     * @param string $prefix     The prefix of a request path.
     * @param string $controller The fully qualified class name of the controller.
     */
    public function __construct($prefix, $controller)
    {
        $this->prefix = $prefix;
        $this->controller = $controller;
    }

    /**
     * {@inheritDoc}
     */
    public function match(Request $request)
    {
        $path = $request->getPathInfo();

        if (strpos($path, $this->prefix) === 0) {
            $controllerReflection = ReflectionUtils::getClass($this->controller);
            $fragments = explode('/', substr($path, strlen($this->prefix)));

            if (empty($fragments[0])) {
                $action = 'index';
                $params = [];
            } elseif (empty($fragments[1])) {
                if (substr($path, -1) === '/') {  // /path/to/{resource}/
                    // Remove the slash
                    throw new HttpRedirectException(substr($path, 0, -1), Response::HTTP_MOVED_PERMANENTLY);
                }
                $action = 'show';
                $params = [$fragments[0]];
            } else {
                $action = $fragments[1];
                $params = array_merge([$fragments[0]], array_slice($fragments, 2));
            }

            return new MatchedRoute($controllerReflection, $action, $params);
        }

        return null;
    }
}
