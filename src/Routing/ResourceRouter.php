<?php

namespace Emonkak\Framework\Routing;

use Emonkak\Framework\Exception\HttpNotFoundException;
use Emonkak\Framework\Exception\HttpRedirectException;
use Emonkak\Framework\Utils\ReflectionUtils;
use Emonkak\Framework\Utils\StringUtils;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class ResourceRouter implements RouterInterface
{
    private $prefix;
    private $controller;

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
